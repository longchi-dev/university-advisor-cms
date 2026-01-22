import Swal from 'sweetalert2';

window.toastSuccess = (msg) =>
    Swal.fire('Thành công!', msg, 'success');

window.toastError = (msg) =>
    Swal.fire('Lỗi!', msg, 'error');
