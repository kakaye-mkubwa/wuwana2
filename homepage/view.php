<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>wuwana.com</title>
	<link rel="icon" type="image/png" href="static/icon.png"/>
	<link rel="stylesheet" type="text/css" href="static/style.css"/>
</head>
<body>
	<div id="popup">
		<div>
			<a href="#" class="button" style="float:right">×</a>
			<img src="static/logo-circle.png" width="96" height="96"><br><br>
			<p>Encontrar un buen proveedor de café de especialidad debería ser más fácil.<br>¿Por qué no lo es?</p>
			<p>Los micro-tostadores no tienen los recursos necesarios para competir con las grandes empresas. Mientras que estas utilizan su dinero para contratar agencias y “expertos del marketing” para aparecer en las primeras páginas de Google, los comercios independientes utilizan todos sus recursos para ofrecer el mejor producto posible. Además, es un esfuerzo enorme convertise en un vendedor de Amazon Business. Aparte de cobrar unas comisiones desorbitadas, Amazon hace el proceso de registro como vendedor una pesadilla.</p>
			<p>Estas barreras dejan a la mayoría de tostadores independientes fuera de la revolución digital. En Wuwana queremos cambiarlo.</p>
			<p>Estamos creando un directorio moderno y digital que de apoyo a las pequeñas cafeterías y a los tostadores locales. Una página honesta y transparente donde serás capaz de filtrar los comercios locales por categoría, visitar sus páginas web para conocerles mejor y contactarles directamente. Trabajamos con un grupo de cafeterías y micro-tostadores de Madrid en la creación de este proyecto.</p>
			<p>Apoya a los comercios independientes del café. Síguenos en Twitter para ser de los primeros en probar nuestra beta a finales de octubre 2020.</p>
			<a href="https://twitter.com/wuwanahq" target="_blank">Síguenos en Twitter</a>
		</div>
	</div>
	<form class="menu" method="get">
		<div class="logo"><a href="/"><img src="static/logo.png"></a></div>
		<dl>
			<dt>Categorías</dt>
			<dt>
				<input id="c" type="checkbox" name="cat" onchange="this.form.submit()"
					<?php if ($selectedCategories == []) { echo 'checked disabled'; } ?>>
				<label for="c">Todas las categorías</label>
			</dt>
			<?php
				foreach ($categories as $id => $category)
				{
					echo '<dd><input type="checkbox" name="cat', $id, '" id="C', $id, '"';

					if (in_array($id, $selectedCategories))
					{ echo ' checked'; }

					echo ' onchange="this.form.submit()"><label for="C', $id, '">', $category->spanish, '</label></dd>';
				}
			?>
		</dl>
		<dl>
			<dt>Comunidades autónomas</dt>
			<dt>
				<input id="r" type="checkbox" name="region" onchange="this.form.submit()"
					<?php if ($selectedRegions == []) { echo 'checked disabled'; } ?>>
				<label for="r">Todas las comunidades</label>
			</dt>
			<?php
				foreach ($locations as $id => $location)
				{
					echo '<dd><input type="checkbox" name="region', $id, '" id="L', $id, '"';

					if (in_array($id, $selectedRegions))
					{ echo ' checked'; }

					echo ' onchange="this.form.submit()"><label for="L', $id, '">', $location->region, '</label></dd>';
				}
			?>
		</dl>
	</form>
	<div class="content">
		<div class="hero" style="position:relative">
			<br><span style="font-size: 4rem; font-weight:900">¿Estás buscando café de especialidad?</span><br>
			<br><span style="font-size:1.5em">Encuentra los proveedores que necesitas rápidamente.</span>
			<br><a href="#popup" class="button" style="position:absolute; bottom:16px; right:16px; font-size: 1rem">
				¿Qué es Wuwana?
			</a>
		</div>
		<span class="title">Las empresas</span>
		<div style="text-align:center; margin-top:24px">
			<?php
				foreach ($companies as $company)
				{
					echo '<div class="card">';

					if ($company->region > 0)
					{
						echo '<span class="geoloc"><img src="static/geoloc.png">',
							$locations[$company->region]->region,
						'</span>';
					}

					echo '<img src="', $company->logo, '">',
						'<br><span class="card-title">', $company->name, '</span>',
						'<div class="description"><br><br>', $company->description, '</div>',						'<hr>';

					foreach ($company->categories as $category)
					{ echo '<span class="button">', $categories[$category]->spanish, '</span>'; }

					echo '<br><br>';

					if (!empty($company->website))
					{ echo '<a href="', $company->website, '" target="_blank">Visit the website</a> &nbsp; '; }

					if (!empty($company->email))
					{ echo '<a href="mailto:', $company->email, '">Contact</a> &nbsp; '; }

					if (!empty($company->phoneNumber))
					{
						echo '<a target="_blank" href="';
						printf(WebApp\Config::WHATSAPP_URL, $company->phoneNumber, $company->name);
						echo '">Whatsapp</a> &nbsp; ';
					}

					echo '</div>';
				}
			?>
		</div>
	</div>
</body>
</html>