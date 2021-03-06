<?php

require __DIR__."/../config/main.php";
require __DIR__."/../config/scraper.php";
require __DIR__."/../bootstrap/icetea_bootstrap.php";

$pdo = DB::pdo();

$st = $pdo->prepare("SELECT `scraped_at` FROM `news` ORDER BY `scraped_at` DESC LIMIT 1;");
$st->execute();
$st = $st->fetch(PDO::FETCH_NUM)[0];

?><!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		.dd {
			display: inline-block;
			border: 1px solid #000;
			padding: 10px;
			height: 1000px;
			margin-bottom: 40px;
		}
		th {
			padding: 5px;
		}
		td {
			padding: 2px;
		}
		table {
			border-collapse:collapse;
			width: 400px;
		}
		button {
			cursor: pointer;
			padding: 4px 10px 4px 10px;
		}
	</style>
	<title>News Scraper</title>
</head>
<body>
	<center>
		<div style="margin-top: 10px; margin-bottom: 30px;">
			<a href="data.php?page=1"><button>Data</button></a>
			<a href="api.php?limit=25&amp;offset=0" target="_blank"><button>API</button></a>
		</div>
		<h1>News Scraper Data Statistics</h1>
		<div style="margin-bottom: 15px;">
			<a href="statistics.php"><button>Advanced Statistics</button></a>
			<a href="check.php"><button>Check Scraper Processes</button></a>
			<h3>This page was loaded at: <?php print date("Y-m-d H:i:s") ?> GMT+7</h3>
			<h3>Latest Update from Scraper: <?php print $st; ?> GMT+7</h3>
		</div>
		<div class="dd">
			<h2>Regional Statistic</h2>
			<table border="1">
				<thead>
					<tr>
						<th>No.</th><th>Regional</th><th>Amount of Data</th>
					</tr>
				</thead>
				<tbody>
<?php 


$st = $pdo->prepare(
	"SELECT `regional`,COUNT(`regional`) AS `total` FROM `news` GROUP BY `regional` ORDER BY `total` DESC;"
);
$st->execute();
$i = 1;
$total = 0;
while ($r = $st->fetch(PDO::FETCH_ASSOC)) {
	$total += $r["total"];
	?>
					<tr><td align="center"><?php print $i++; ?>.</td><td align="center"><?php print $r["regional"]; ?></td><td align="center"><?php print $r["total"]; ?></td></tr>
	<?php
}
?>
			</tbody>
			<tfoot>
				<tr><td colspan="2" align="center">Total Data</td><td align="center"><?php print $total; ?></td></tr>
			</tfoot>
			</table>
		</div>
		<div class="dd">
			<h2>Sites Statistic</h2>
			<table border="1">
				<thead>
					<tr>
						<th>No.</th><th>Regional</th><th>Amount of Data</th>
					</tr>
				</thead>
				<tbody>
<?php

$sites = [
	"Tribunnews" => "tribunnews.com",
	"Liputan6" => "liputan6.com",
	"Detik" => "detik.com",
	"Kompas" => "kompas.com",
	"Antaranews" => "antaranews.com",
	"Suara" => "suara.com",
	"Viva" => "viva.co.id",
	"Jpnn" => "jpnn.com",
	"Kabardaerah" => "kabardaerah.com",
	"Indonesiatimur" => "indonesiatimur.co",
	"Matatelinga" => "matatelinga.com",
	"Beritasumut" => "beritasumut.com",
	"Gosumut" => "gosumut.com",
	"Hargasumut" => "hargasumut.org",
	"Kabarsumbar" => "kabarsumbar.com",
	"Hariansinggalang" => "hariansinggalang.co.id",
	"Minangkabaunews" => "minangkabaunews.com",
	"Kabarpadang" => "kabarpadang.com",
	"Gosumbar" => "gosumbar.com",
	"Harianhaluan" => "harianhaluan.com",
	"Infosumbar" => "infosumbar.net",
	"Beritasumbar" => "beritasumbar.com",
	"Redaksisumbar" => "redaksisumbar.com",
	"Riauterkini" => "riauterkini.com",
	"Goriau" => "goriau.com",
	"Riaugreen" => "riaugreen.com",
	"Beritariau" => "beritariau.com",
	"Riausky" => "riausky.com",
	"Inforiau" => "inforiau.co",
	"Infoinhil" => "infoinhil.com",
	"Sijorikepri" => "sijorikepri.com",
	"Lendoot" => "lendoot.com",
	"Tanjungpinangpos" => "tanjungpinangpos.co.id",
	"Batamtoday" => "batamtoday.com",
	"Batampos" => "batampos.co.id",
	"Lintaskepri" => "lintaskepri.com",
	"Visitkepri" => "visitkepri.com",
	"Kepri.co.id" => "kepri.co.id",
	"Infokepri" => "infokepri.com",
	"Keprionline" => "keprionline.co.id",
	"Kepri.info" => "kepri.info",
	"Infojambi" => "infojambi.com",
	"Jambiupdate" => "jambiupdate.co",
	"Metrojambi" => "metrojambi.com",
	"Jambiindependent" => "jambi-independent.co.id",
	"Fokusjambi" => "fokusjambi.com",
	"Jambiekspres" => "jambiekspres.co.id",
	"Beritajambi" => "beritajambi.co",
	"Detiksumsel" => "detiksumsel.com",
	"Palembangpos" => "palembang-pos.com",
	"Rmolsumsel" => "rmolsumsel.com",
	"Beritapagi" => "beritapagi.co.id",
	"Kabarsumatera" => "kabarsumatera.com",
	"Inradiofm" => "inradiofm.com",
	"Kabarbangka" => "kabarbangka.com",
	"Radarbangka" => "radarbangka.co.id",
	"Rakyatpos" => "rakyatpos.com",
	"Kabarbabel" => "kabarbabel.com",
	"Reportasebangka" => "reportasebangka.com",
	"Beritabangka" => "beritabangka.com",
	"Beritababel" => "beritababel.com",
	"Rmolbabel" => "rmolbabel.com",
	"Rbtv" => "rbtv.co.id",
	"Harianrakyatbengkulu" => "harianrakyatbengkulu.com",
	"Radarbengkuluonline" => "radarbengkuluonline.com",
	"Liputanbengkulu" => "liputanbengkulu.com",
	"Bengkuluekspress" => "bengkuluekspress.com",
	"Bengkulutoday" => "bengkulutoday.com",
	"Rmolbengkulu" => "rmolbengkulu.com",
	"Pedomanbengkulu" => "pedomanbengkulu.com",
	"Radarlampung" => "radarlampung.co.id",
	"Infolampung" => "infolampung.co",
	"Lampost" => "lampost.co",
	"Kupastuntas" => "kupastuntas.co",
	"Infojakarta" => "infojakarta.net",
	"Beritajakarta" => "beritajakarta.id",
	"Jakartakita" => "jakartakita.com",
	"Kabarjakarta" => "kabarjakarta.com",
	"Aktual" => "aktual.com",
	"Pikiranrakyat" => "pikiran-rakyat.com",
	"Bandungberita" => "bandungberita.com",
	"Republika" => "republika.co.id",
	"Kabarjabar" => "kabarjabar.com",
	"Postkeadilan" => "postkeadilan.com",
	"Jabarekspres" => "jabarekspres.com",
	"Jabarnews" => "jabarnews.com",
	"Radarbanten" => "radarbanten.co.id",
	"Kabarbanten" => "kabar-banten.com",
	"Infobanten" => "infobanten.com",
	"Banteninfo" => "banteninfo.com",
	"Bantennews" => "bantennews.co.id",
	"Kabartangsel" => "kabartangsel.com",
	"Beritajateng" => "beritajateng.net",
	"Metrojateng" => "metrojateng.com",
	"Kabarjateng" => "kabarjateng.co.id",
	"Jogja" => "jogja.co",
	"Kotajogja" => "kotajogja.com",
	"Infojogja" => "info-jogja.com",
	"Krjogja" => "krjogja.com",
	"Beritajatim" => "beritajatim.com",
	"Infojatim" => "infojatim.com",
	"Kabarjatim" => "kabarjatim.com",
	"Bisnis" => "bisnis.com",
	"Kabarjawatimur" => "kabarjawatimur.com",
	"Beritabali" => "beritabali.com",
	"Balipost" => "balipost.com",
	"Nusabali" => "nusabali.com",
	"Suarantb" => "suarantb.com",
	"Mataramnews" => "mataramnews.co.id",
	"Wartantb" => "wartantb.com",
	"Hariannusa" => "hariannusa.com",
	"Lombokita" => "lombokita.com",
	"Voxntt" => "voxntt.com",
	"Kalbaronline" => "kalbaronline.com",
	"Kilaskalbar" => "kilaskalbar.com",
	"Rmolkalbar" => "rmolkalbar.com",
	"Prokal" => "prokal.co",
	"Borneonews" => "borneonews.co.id",
	"Beritasampit" => "beritasampit.co.id",
	"Kaltengnews" => "kaltengnews.com",
	"Beritakalteng" => "beritakalteng.com",
	"Gerakkalteng" => "gerakkalteng.com",
	"Kliksamarinda" => "kliksamarinda.com",
	"Korankaltim" => "korankaltim.com",
	"Beritamanado" => "beritamanado.com",
	"Sulutdaily" => "sulutdaily.com",
	"Manadoline" => "manadoline.com",
	"Suaramanado" => "suaramanado.com",
	"Rmol" => "rmol.co",
	"Sulutonline" => "sulutonline.com",
	"Cybersulutnews" => "cybersulutnews.co.id",
	"Pilarsulut" => "pilarsulut.com",
	"Sulutpos" => "sulutpost.com",
	"Fajar" => "fajar.co.id",
	"Suryatopnews" => "suryatopnews.com",
	"Fajaronline" => "fajaronline.co.id",
	"Sulbarkita" => "sulbarkita.com",
	"Jurnalsulbar" => "jurnalsulbar.com",
	"Mediasulbar" => "mediasulbar.com",
	"Radarsultengonline" => "radarsultengonline.com",
	"Kabarsulteng" => "kabarsulteng.com",
	"Tempo" => "tempo.co",
	"Satusulteng" => "satusulteng.com",
	"Beritasulteng" => "beritasulteng.id",
	"Zonasultra" => "zonasultra.com",
	"Sultrakini" => "sultrakini.com",
	"Kabarmakassar" => "kabarmakassar.com",
	"Terkini" => "terkini.id",
	"Beritasulsel" => "berita-sulsel.com",
	"Okezone" => "okezone.com",
	"Merdeka" => "merdeka.com",
	"Radargorontalo" => "radargorontalo.com",
	"Mimoza" => "mimoza.tv",
	"Vifapost" => "vifapost.com",
	"Tribunmaluku" => "tribun-maluku.com",
	"Rakyatmaluku" => "rakyatmaluku.com",
	"Detikmaluku" => "detikmaluku.com",
	"Tajuktimur" => "tajuktimur.com",
	"Terasmaluku" => "terasmaluku.com",
	"Beritamalut.co" => "beritamalut.co",
	"Beritamalut.com" => "beritamalut.com",
	"Beritamalukuonline" => "beritamalukuonline.com",
	"Deliknews" => "deliknews.com",
	"Indonesiaberita" => "indonesiaberita.com",
	"Beritapapua" => "berita-papua.com",
	"Papuanews" => "papuanews.id",
	"Pasificpos" => "pasificpos.com",
	"Kabarpapua" => "kabarpapua.co",
	"Tabloidjubi" => "tabloidjubi.com",
	"Tribratanewspapuabarat" => "tribratanewspapuabarat.com",
	"Papuatoday" => "papuatoday.com",
	"Papua.us" => "papua.us",
	"Lensapapua" => "lensapapua.com",
];
$i = 1;
$total = 0;
foreach ($sites as $key => $site) {
	$pdo = DB::pdo();
	$st = $pdo->prepare(
		"SELECT COUNT(`url`) AS `total` FROM `news` WHERE `url` LIKE :site ORDER BY `total` DESC;"
	);
	$st->execute([":site" => "%".$site."%"]);
	while ($r = $st->fetch(PDO::FETCH_NUM)) {
		$total += $r[0];
		?>
					<tr><td align="center"><?php print $i++; ?>.</td><td align="center"><?php print $key; ?></td><td align="center"><?php print $r[0]; ?></td></tr>
<?php
	}
}
?>
			</tbody>
			<tfoot>
				<tr><td colspan="2" align="center">Total Data</td><td align="center"><?php print $total; ?></td></tr>
			</tfoot>
			</table>
		</div>
	</center>
</body>
</html><?php unset($pdo); ?>