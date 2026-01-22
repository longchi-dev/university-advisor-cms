$(document).ajaxSend(function (event, xhr) {
    xhr.setRequestHeader(
        'X-CSRF-TOKEN',
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
    );
});
