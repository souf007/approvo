<?php
// Updated KPI Payments Section
if($_POST['action'] == "loadkpi"){
    $req = "";
    $datestart = time() - (60*60*24*30);
    $dateend = time();
    if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
        $datestart = strtotime(str_replace("/","-",$_POST['datestart']));
        $dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
    }
    $req .= " AND (d.dateadd BETWEEN ".$datestart." AND ".$dateend.")";
    if($_POST['company'] != ""){
        $comp = explode(",",$_POST['company']);
        $req .= " AND (";
        for($j=0;$j<count($comp);$j++){
            if($j==0){
                $req .= "FIND_IN_SET(".$comp[$j].", d.company)";
            }
            else{
                $req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
            }
        }
        $req .= ")";
    }
    if($_POST['client'] != "" AND $_POST['supplier'] != ""){
        $req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
    }
    else{
        if($_POST['client'] != ""){
            $req .= " AND d.client IN(".$_POST['client'].")";
        }
        if($_POST['supplier'] != ""){
            $req .= " AND d.supplier IN(".$_POST['supplier'].")";
        }
    }
    $req .= $multicompanies;
    $back = $bdd->query("SELECT SUM((CASE WHEN typedoc='facture' THEN tprice WHEN typedoc='avoir' THEN tprice*(-1) ELSE 0 END) * (1 + (tva / 100))) AS sm,
                              SUM((CASE WHEN typedoc='facture' THEN tprice WHEN typedoc='avoir' THEN tprice*(-1) ELSE 0 END) * (tva / 100)) AS stva FROM detailsdocuments d WHERE typedoc IN('facture','avoir') AND trash='1'".$req);
    $ca = $back->fetch();
    $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND invoiced='Oui' AND doc='0'".$req);
    $entree = $back->fetch();
    ?>
    <div class="lx-g4 lx-pr-0 lx-pb-0 lx-plr-0-mob">
        <div class="kpi-card kpi-revenue" style="background:<?php echo ($ca['sm']+$entree['sm'])>0?"linear-gradient(135deg, #28a745 0%, #20c997 100%)":(($ca['sm']+$entree['sm'])<0?"linear-gradient(135deg, #dc3545 0%, #fd7e14 100%)":"linear-gradient(135deg, #6c757d 0%, #adb5bd 100%)");?>;">
            <div class="kpi-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">Chiffre d'affaires TTC</div>
                <div class="kpi-value"><?php echo number_format((float)($ca['sm']+$entree['sm']),2,"."," ")." ".$settings['currency'];?></div>
                <div class="kpi-subtitle">CA = (Factures + Encaissements) - Avoirs</div>
                <?php
                $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND invoiced='Non' AND doc='0'".$req);
                $entree = $back->fetch();
                ?>
                <div class="kpi-detail">
                    <span class="kpi-badge"><?php echo number_format((float)($entree['sm']),2,"."," ")." ".$settings['currency'];?></span>
                    <span class="kpi-label">Non facturé</span>
                </div>
            </div>
        </div>
    </div>
    <?php
    $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND paid='0' AND invoiced='Oui' AND trash='1'".$req);
    $entree = $back->fetch();
    ?>
    <div class="lx-g4 lx-pb-0 lx-plr-0-mob">
        <div class="kpi-card kpi-income" style="background:linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
            <div class="kpi-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">Encaissements TTC</div>
                <div class="kpi-value"><?php echo number_format((float)$entree['sm'],2,"."," ")." ".$settings['currency'];?></div>
                <div class="kpi-subtitle">Paiements reçus</div>
                <?php
                $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND paid='0' AND invoiced='Non' AND doc='0'".$req);
                $entree = $back->fetch();
                ?>
                <div class="kpi-detail">
                    <span class="kpi-badge"><?php echo number_format((float)($entree['sm']),2,"."," ")." ".$settings['currency'];?></span>
                    <span class="kpi-label">Non facturé</span>
                </div>
            </div>
        </div>
    </div>
    <?php
    $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Sortie' AND paid='0' AND invoiced='Oui' AND trash='1'".$req);
    $sortie = $back->fetch();
    ?>
    <div class="lx-g4 lx-pl-0 lx-pb-0 lx-plr-0-mob">
        <div class="kpi-card kpi-expenses" style="background:linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);">
            <div class="kpi-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">Dépenses TTC</div>
                <div class="kpi-value"><?php echo number_format((float)$sortie['sm'],2,"."," ")." ".$settings['currency'];?></div>
                <div class="kpi-subtitle">Paiements effectués</div>
                <?php
                $back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Sortie' AND paid='0' AND invoiced='Non' AND doc='0'".$req);
                $sortie = $back->fetch();
                ?>
                <div class="kpi-detail">
                    <span class="kpi-badge"><?php echo number_format((float)($sortie['sm']),2,"."," ")." ".$settings['currency'];?></span>
                    <span class="kpi-label">Non facturé</span>
                </div>
            </div>
        </div>
    </div>
    <?php
    $back = $bdd->query("SELECT SUM(price * (tva / 100)) AS sm FROM payments d WHERE type='Entrée'".$req);
    $tvaentree = $back->fetch();
    $back = $bdd->query("SELECT SUM(price * (tva / 100)) AS sm FROM payments d WHERE type='Sortie'".$req);
    $tvasortie = $back->fetch();
    $back = $bdd->query("SELECT SUM(tprice * (tva / 100)) AS stva FROM detailsdocuments d WHERE typedoc='bc' AND trash='1'".$req);
    $tva = $back->fetch();				
    ?>
    <div class="lx-g4 lx-pl-0 lx-pb-0 lx-plr-0-mob">
        <div class="kpi-card kpi-tax" style="background:linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
            <div class="kpi-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">
                    <?php
                    if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles']) AND preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
                        ?>
                    TVA Collectée / Payée
                        <?php
                    }
                    elseif(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
                        ?>
                    TVA Collectée
                        <?php
                    }
                    elseif(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
                        ?>
                    TVA Payée
                        <?php
                    }
                    ?>
                </div>
                <div class="kpi-value">
                    <?php
                    if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles']) AND preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
                        ?>
                    <?php echo number_format((float)$ca['stva']+$tvaentree['sm'],2,"."," ")." ".$settings['currency']." / ".number_format((float)$tva['stva']+$tvasortie['sm'],2,"."," ")." ".$settings['currency'];?>
                        <?php
                    }
                    elseif(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
                        ?>
                    <?php echo number_format((float)$ca['stva']+$tvaentree['sm'],2,"."," ")." ".$settings['currency'];?>
                        <?php
                    }
                    elseif(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
                        ?>
                    <?php echo number_format((float)$tva['stva']+$tvasortie['sm'],2,"."," ")." ".$settings['currency'];?>
                        <?php
                    }
                    ?>
                </div>
                <div class="kpi-subtitle">Taxe sur la valeur ajoutée</div>
            </div>
        </div>
    </div>
    <div class="lx-clear-fix"></div>
    <?php
}

// Updated KPI Documents Section
if($_POST['action'] == "loaddocuments"){
    $req = "";
    $datestart = time() - (60*60*24*30);
    $dateend = time();
    if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
        $datestart = strtotime(str_replace("/","-",$_POST['datestart']));
        $dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
    }
    $req .= " AND (d.dateadd BETWEEN ".$datestart." AND ".$dateend.")";
    if($_POST['company'] != ""){
        $comp = explode(",",$_POST['company']);
        $req .= " AND (";
        for($j=0;$j<count($comp);$j++){
            if($j==0){
                $req .= "FIND_IN_SET(".$comp[$j].", d.company)";
            }
            else{
                $req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
            }
        }
        $req .= ")";
    }
    if($_POST['client'] != "" AND $_POST['supplier'] != ""){
        $req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
    }
    else{
        if($_POST['client'] != ""){
            $req .= " AND d.client IN(".$_POST['client'].")";
        }
        if($_POST['supplier'] != ""){
            $req .= " AND d.supplier IN(".$_POST['supplier'].")";
        }
    }
    $req .= $multicompanies;
    ?>
    <div class="document-kpi-grid">
    <?php
    if(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='facture' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="factures.php" class="document-kpi-card document-facture">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 class="document-kpi-title">Factures</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Devis#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='devis' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="devis.php" class="document-kpi-card document-devis">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3 class="document-kpi-title">Devis</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Factures avoir#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='avoir' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="avoirs.php" class="document-kpi-card document-avoir">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h3 class="document-kpi-title">Factures avoir</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Bons de retour#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='br' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="br.php" class="document-kpi-card document-br">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <h3 class="document-kpi-title">Bons de retour</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Factures proforma#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='factureproforma' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="factureproforma.php" class="document-kpi-card document-factureproforma">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h3 class="document-kpi-title">Factures proforma</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Bons de livraison#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bl' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="bl.php" class="document-kpi-card document-bl">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="document-kpi-title">Bons de livraison</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Bons de sortie#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bs' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="bs.php" class="document-kpi-card document-bs">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3 class="document-kpi-title">Bons de sortie</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bc' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="bc.php" class="document-kpi-card document-bc">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="document-kpi-title">Bons de commande</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    if(preg_match("#Consulter Bons de réception#",$_SESSION['easybm_roles'])){
        $back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bre' AND trash='1'".$req);
        $row = $back->fetch();
        ?>
        <a href="bre.php" class="document-kpi-card document-bre">
            <div class="document-kpi-header">
                <div class="document-kpi-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3 class="document-kpi-title">Bons de réception</h3>
            </div>
            <div class="document-kpi-stats">
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Nombre</div>
                    <div class="document-kpi-stat-value"><?php echo $row['nb'];?></div>
                </div>
                <div class="document-kpi-stat">
                    <div class="document-kpi-stat-label">Valeur</div>
                    <div class="document-kpi-stat-value"><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></div>
                </div>
            </div>
        </a>
        <?php
    }
    ?>
    </div>
    <div class="lx-clear-fix"></div>
    <?php
}
?> 