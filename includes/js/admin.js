document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.nav-tab');
    var contents = document.querySelectorAll('.tab-content');
    var activeTab = localStorage.getItem('activeTab') || 'core-web-vitals';

    if (!document.querySelector('#' + activeTab)) {
        activeTab = 'core-web-vitals';
    }

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

    if (document.querySelector('#' + activeTab)) {
        document.querySelector('#' + activeTab).style.display = 'block';
    }
    if (document.querySelector('#' + activeTab + '-tab')) {
        document.querySelector('#' + activeTab + '-tab').classList.add('nav-tab-active');
    }
});
