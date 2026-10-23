
<!--**********************************
            Content body end
        ***********************************-->

<!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                
                <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a> 2018</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
    <script src="./plugins/select2/js/select2.full.min.js"></script>
    <script src="./plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="./plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="js/mark.js"></script>
    <script src="./plugins/tables/js/datatable-init/datatable-basic.min.js"></script>
    <script src="js/custom-script.js"></script>

    <?php
   if ( basename($_SERVER['PHP_SELF']) == 'add-industries.php' || basename($_SERVER['PHP_SELF']) == 'add-technology.php' || basename($_SERVER['PHP_SELF']) == 'add-program-lang.php' || basename($_SERVER['PHP_SELF']) == 'add-framework.php' || basename($_SERVER['PHP_SELF']) == 'add-portfolio_type.php' ) {
        ?>
        <script src="./plugins/validation/jquery.validate.min.js"></script>
        <script src="./plugins/validation/jquery.validate-init.js"></script>
        <?php
    }   
   ?>
</body>

</html>