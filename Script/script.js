document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert');
    for (var i = 0; i < alerts.length; i++) {
        (function(alert){
            setTimeout(function() {
                alert.classList.add('hide');
            }, 3000);
        })(alerts[i]);
    }
});
