<script src="\assets\js\libs\jquery-3.1.1.min.js"></script>
<script src="\bootstrap\js\popper.min.js"></script>
<script src="\bootstrap\js\bootstrap.min.js"></script>
<script src="\plugins\perfect-scrollbar\perfect-scrollbar.min.js"></script>
<script src="/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="\assets\js\app.js"></script>
<script defer src="/assets/cookieconsent.js"></script>
<script src="/assets/demo.js" defer></script>
<script defer src="/assets/cookieconsent-init.js"></script>

<script>
$(document).ready(function() {
    App.init();
});
function selectLanguage(languageCode) {
    document.body.innerHTML += '<form id="langForm" action="' + window.location.href + '" method="post">' +
        '<input type="hidden" name="lang" value="' + languageCode + '"></form>';
    document.getElementById("langForm").submit();
}

function selectCurrency(currencyCode) {
    document.body.innerHTML += '<form id="currencyForm" action="' + window.location.href + '" method="post">' +
        '<input type="hidden" name="currency" value="' + currencyCode + '"></form>';
    document.getElementById("currencyForm").submit();
}
</script>
<script src="\assets\js\custom.js"></script>
<script src="\plugins\apex\apexcharts.min.js"></script>
    <!-- BEGIN PAGE LEVEL CUSTOM SCRIPTS -->
    <script src="\assets\js\scrollspyNav.js"></script>
    <script>
        checkall('todoAll', 'todochkbox');
        $('[data-toggle="tooltip"]').tooltip()
    </script>
 <style>
    /*FAB*/
        .fontawesome .icon-section {
            padding: 30px;
        }
        .fontawesome .icon-section h4 {
            color: #3b3f5c;
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            margin-bottom: 16px;
        }
        .fontawesome .icon-content-container {
            padding: 0 16px;
            width: 86%;
            margin: 0 auto;
            border: 1px solid #bfc9d4;
            border-radius: 6px;
        }
        .fontawesome .icon-section p.fs-text {
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        .fontawesome .icon-container { cursor: pointer; }
        .fontawesome .icon-container i {
            font-size: 20px;
            color: #3b3f5c;
            vertical-align: middle;
            margin-right: 10px;
        }
        .fontawesome .icon-container:hover i { color: #4361ee; }
        .fontawesome .icon-container span { color: #888ea8; display: none; }
        .fontawesome .icon-container:hover span { color: #4361ee; }
        .fontawesome .icon-link {
            color: #4361ee;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
