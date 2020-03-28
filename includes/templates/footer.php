

<script src="js/sweetalert2.all.min.js"></script>
    <?php
        $actual=obtenerArchivo();

        if ($actual==='crear-cuenta' || $actual==='login') {
            echo '<script src="js/formulario.js?v=v=1_0"></script>';  
        }
        elseif ($actual==='index') {
            echo '<script src="js/scripts.js?v=v=1_0"></script>';
        }
    ?>

</body>
</html>