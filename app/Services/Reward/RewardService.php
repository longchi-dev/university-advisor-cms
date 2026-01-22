<?php

namespace App\Services\Reward;

use App\Models\GamingSession;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\HashHelper;
use App\Models\AwardeePhysical;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RewardService
{
    public function __construct()
    {

    }

    public function getReward($id, User $admin): ?GiveRewardDto
    {
        $query = AwardeePhysical::query()
            ->join('awardees', function ($join) {
                $join->on('awardee_physical.awardee_id', '=', 'awardees.id');
            })
            ->join('region_group', function ($join) {
                $join->on('awardee_physical.region_group_id', '=', 'region_group.id');
            })
            ->join('reward_entries', function ($join) {
                $join->on('reward_entries.id', '=', 'awardees.reward_entry_id');
            })
            ->join('rewards', function ($join) {
                $join->on('reward_entries.reward_id', '=', 'rewards.id');
            })
            ->join('pnj_physical_reward', function ($join) {
                $join->on('pnj_physical_reward.reward_id', '=', 'rewards.id');
            })
            ->where('reward_entries.is_claimed', 1)
            #->where('reward_entries.is_used', 0)
            ->where('awardee_physical.id', $id)
            ->selectRaw('rewards.name, rewards.description, awardees.reward_entry_id, awardees.awardee_id, awardees.awarded_at, reward_entries.is_used, reward_entries.used_at');

        if ($admin->area) {
//            $query->where(function ($q) use ($admin) {
//                $q->where('region_group.pnj_area_code', $admin->area)
//                    ->orWhereIn('pnj_physical_reward.type', [
//                        'giai1phuquy',
//                        'giai2phuquy',
//                        'giai3phuquy',
//                    ])
//                ;
//            });
        }

        $record = $query->first();
        return $record ?
            new GiveRewardDto(
                $record->reward_entry_id,
                $record->awardee_id,
                $record->name,
                $record->description,
                $record->awarded_at,
                $record->is_used,
                $record->used_at
            )
            : null;
    }

    public function filterReward(array $data, User $admin): LengthAwarePaginator|Collection
    {
        if (isset($data['phone'])) {
            $user = User::query()->where('phone_hashed', HashHelper::hashPhoneForPnj(phone: $data['phone']))->first();
        }else if (isset($data['phone_hashed'])){
            $user = User::query()->where('phone_hashed', $data['phone_hashed'])->first();
        }

        if (!empty($data['order_code']) || !empty($data['store_id'])){
            $billIds = GamingSession::query()
                ->join('bills', function ($join) {
                    $join->on('gaming_sessions.bill_id', '=', 'bills.id');
                })
                ->when(!empty($data['order_code']), function ($query) use ($data) {
                    $query->where('order_code', $data['order_code']);
                })
                ->when(!empty($data['store_id']), function ($query) use ($data) {
                    $query->where('store_id', $data['store_id']);
                })
                ->select('bills.id')
                ->pluck('bills.id');
        }

        $query = AwardeePhysical::query()
            ->join('awardees', function ($join) {
                $join->on('awardee_physical.awardee_id', '=', 'awardees.id');
            })
            ->join('region_group', function ($join) {
                $join->on('awardee_physical.region_group_id', '=', 'region_group.id');
            })
            ->join('reward_entries', function ($join) {
                $join->on('reward_entries.id', '=', 'awardees.reward_entry_id');
            })
            ->join('rewards', function ($join) {
                $join->on('reward_entries.reward_id', '=', 'rewards.id');
            })
            ->join('users', function ($join) {
                $join->on('awardees.awardee_id', '=', 'users.id');
            })
            ->join('pnj_physical_reward', function ($join) {
                $join->on('pnj_physical_reward.reward_id', '=', 'rewards.id');
            })
            //TODO Có trường hợp không có gaming_sessions nhưng vẫn trúng quà
            ->leftJoin('gaming_sessions', function ($join) {
                $join->on('gaming_sessions.awardee_id', '=', 'awardees.id');
            })
            ->where('reward_entries.is_claimed', 1)
            #->where('reward_entries.is_used', 0)
            ->selectRaw(
                'pnj_physical_reward.type, awardee_physical.giver, awardee_physical.id as awardee_physical_id, users.mask_phone, users.phone_hashed, region_group.pnj_area_code, rewards.name, rewards.description, awardees.reward_entry_id, awardees.awardee_id, awardees.id, awardees.awarded_at, reward_entries.is_used, reward_entries.used_at'
            );
        if (isset($user)){
            $query->where('awardees.awardee_id', operator: $user->getKey());
        }
//        if ($admin->area) {
//            $query->where(function ($q) use ($admin) {
//                $q->where('region_group.pnj_area_code', $admin->area)
//                    ->orWhereIn('pnj_physical_reward.type', [
//                        'giai1phuquy',
//                        'giai2phuquy',
//                        'giai3phuquy',
//                    ])
//                ;
//            });
//        }
        if (!empty($data['region_code'])) {
            $query->where('region_group.pnj_area_code', $data['region_code']);
        }
        if (!empty($data['awarded_at'])) {
            $awarded_at = Carbon::parse($data['awarded_at']);
            $query->whereDate('awardees.awarded_at', $awarded_at);
        }
        if (!empty($data['reward_name'])) {
            $query->where('rewards.name', 'LIKE', '%'. $data['reward_name'] .'%');
        }
        if (!empty($data['description_reward'])) {
            $query->where('rewards.description', 'LIKE', '%'. $data['description_reward'] .'%');
        }
        if (!empty($data['status'])) {
            if ($data['status'] == 1){
                $query->where('reward_entries.is_used', 1);
            }else{
                $query->where('reward_entries.is_used', 0);
            }
        }
        if (!empty($data['giver'])){
            $query->where('awardee_physical.giver', $data['giver']);
        }
        if (isset($billIds)){
            $query->whereIn('gaming_sessions.bill_id', $billIds);
        }
        $query->orderBy('awardees.awarded_at', 'desc');
        if (isset($data['export']) && $data['export'] === true) {
            return $query->get(); // Lấy toàn bộ dữ liệu
        }

        return $query->paginate();
    }
}
