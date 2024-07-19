document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.nav-tab');
    var contents = document.querySelectorAll('.tab-content');
    var activeTab = localStorage.getItem('activeTab') || 'frontend';

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (event) {
            event.preventDefault();
            var target = this.getAttribute('href').substring(1);

            tabs.forEach(function (tab) {
                tab.classList.remove('nav-tab-active');
            });
            contents.forEach(function (content) {
                content.style.display = 'none';
            });

            document.querySelector('#' + target).style.display = 'block';
            this.classList.add('nav-tab-active');
            localStorage.setItem('activeTab', target);
        });
    });

    contents.forEach(function (tab) {
        tab.style.display = 'none';
    });

    document.querySelector('#' + activeTab).style.display = 'block';
    document.querySelector('#' + activeTab + '-tab').classList.add('nav-tab-active');
});