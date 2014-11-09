            

<footer>
    <div id="logoClientes">
        <ul>
            <li>Secretaría de Planeación<br/><span><a href="http://www.sdp.gov.co" target="_blank" >www.sdp.gov.co</a></span></li>
            <li>Secretaría Distrital del Habitat<br/><span><a href="http://www.habitatbogota.gov.co" target="_blank" >www.habitatbogota.gov.co</a></span></li>
            <li>Secretaría de Salud<br/><span><a href="http://www.saludcapital.gov.co" target="_blank" >www.saludcapital.gov.co</a></span></li>
            <li>Ambiente Bogotá<br/><span><a href="http://www.ambientebogota.gov.co/" target="_blank" >www.ambientebogota.gov.co</a></span></li>
            <li>Ministerio de Ambiente<br/><span><a href="http://www.minambiente.gov.co" target="_blank" >www.minambiente.gov.co</a></span></li>
            <li>Sociedad Col Ingenieros<br/><span><a href="http://www.sci.org.co/" target="_blank" >www.sci.org.co/</a></span></li>
            
        </ul>
    </div>
    <div id="menuFooter">
        <div id="menuFooterUl">
            <? if (isset($this->menu)) echo $this->menu; ?>
        </div>
    </div>
    <div id="footer">
        <p>Copyright urbanaconsultores.com.co</p> 
    </div>
</footer>  
<?
$countJs = count($js);
if (isset($js) && $countJs):
    for ($i = 0; $i < $countJs; $i++):
        ?><script src="<?php echo $js[$i] ?>" type="text/javascript"></script><?
        echo '
';
    endfor;
endif;
?>

</body>

</html>