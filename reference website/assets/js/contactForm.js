(function() {
    // https://dashboard.emailjs.com/admin/integration
    emailjs.init('user_tiJOs8dQgqsQ9M1gD9508');
})();

window.onload = function() {
    document.getElementById('contact-form').addEventListener('submit', function(event) {
        event.preventDefault();
        // generate a five digit number for the contact_number variable
        this.contact_number.value = Math.random() * 100000 | 0;
        // these IDs from the previous steps
        emailjs.sendForm('contact_service', 'contact_tailorEd_mhg9gkn', this)
            .then(function() {
                console.log('SUCCESS!');
                let form = document.getElementById('contact-form');
                form.reset();
            }, function(error) {
                console.log('FAILED...', error);
            });
    });
}
