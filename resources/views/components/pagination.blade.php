<nav aria-label="Pagination" class="mt-3 d-flex justify-content-between">
  <ul class="pagination justify-content-center">
      <!-- Previous Page -->
      @if ($data->previousPageUrl())
          <li class="page-item">
              <a class="page-link" href="{{ $data->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
              </a>
          </li>
      @else
          <li class="page-item disabled">
              <span class="page-link" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
              </span>
          </li>
      @endif

      @php
          $maxPagesToShow = (int) config('manulife.max_page_to_show');
          $currentPage = $data->currentPage();
          $lastPage = $data->lastPage();

          $startPage = max(1, $currentPage - floor($maxPagesToShow / 2));
          $endPage = min($lastPage, $startPage + $maxPagesToShow - 1);

          // Điều chỉnh startPage nếu endPage đạt giới hạn cuối
          $startPage = max(1, $endPage - $maxPagesToShow + 1);
      @endphp

      <!-- Page Numbers -->
      @for ($page = $startPage; $page <= $endPage; $page++)
          <li class="page-item {{ $page == $data->currentPage() ? 'active' : '' }}">
              <a class="page-link" href="{{ $data->appends(request()->query())->url($page) }}">{{ $page }}</a>
          </li>
      @endfor

      <!-- Next Page -->
      @if ($data->nextPageUrl())
          <li class="page-item">
              <a class="page-link" href="{{ $data->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
              </a>
          </li>
      @else
          <li class="page-item disabled">
              <span class="page-link" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
              </span>
          </li>
      @endif
  </ul>

  <div>
    <p>
        Hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }} của {{ $data->total() }} dữ liệu.
    </p>
  </div>
</nav>
