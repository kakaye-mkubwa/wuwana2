<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<?php include '../Templates/page metadata.php' ?>
	<meta name="twitter:title" content="<?php echo $company->name ?> | Wuwana">
	<meta property="og:title" content="<?php echo $company->name ?> | Wuwana" />
	<title><?php echo $company->name ?> | Wuwana</title>
</head>
<body>
	<?php include '../Templates/page header.php' ?>
	<div class="Container">
		<div class="ColumnLeft Company">
			<div class="Box Profile">
				<section class="CompanyAbout">
					<div class="Logo">
						<img src="<?php echo $company->logo ?>">
					</div>
					<h1><?php echo $company->name ?></h1>
					<?php
						if (isset($user) && $user->isLogin() && $user->isAdmin())
						{
							echo '<form method="post">';
							echo  '<textarea></textarea><br>';
							echo  '<input type="submit" value="Update description">';
							echo '</form>';
						}
					?>
					<ul class="Label">
						<?php
							foreach ($company->tags as $tag)
							{ echo '<li>', $tag, '</li>'; }
						?>
					</ul>
					<div class="Tag Region">Cataluna</div>
				</section>
				<section class="CompanyDescription">
					<hr>
					<?php
						if (isset($user) && $user->isLogin() && $user->isAdmin())
						{
							echo '<form method="post">';
							echo  '<input type="text" placeholder="New company"><br>';
							echo  '<input type="submit" value="Update name">';
							echo '</form>';
						}
						else
						{
							echo '<h3>', $company->description, '</h3>';
						}
					?>
					<br><br>
				</section>
				<section class="CompanyWhy">
					<hr>
					<h3><?php printf(TEXT[0], $company->name) ?></h3>
					<ul>
						<li>
							<div class="ItemLabel">
								<div class="GoogleReview">
										4,8
										<span class="ReviewScale">/5</span>
								</div>
								Google review
							</div>
						</li>
						<li>
							<div class="ItemLabel">
								<img src="/static/badge/sustainability.svg">
								Sostenible
							</div>
						</li>
						<li>
							<div class="ItemLabel">
								<img src="/static/badge/social-impact.svg">
								Compromiso social
							</div>
						</li>
					</ul>
				</section>
				<section class="ContactInfo">
					<hr>
					<h3><?php printf(TEXT[1], $company->name) ?></h3>
					<ul>
						<li>
							<a href="/">
								<div class="ItemLabel">
									<div class="Button Circle">
										<img src="/static/icon/instagram.svg">
									</div>
									Instagram
								</div>
							</a>
						</li>
						<li>
							<a href="/">
								<div class="ItemLabel">
									<div class="Button Circle">
										<img src="/static/icon/whatsapp.svg">
									</div>
									Whatsapp
								</div>
							</a>
						</li>
					</ul>
				</section>
				<?php
					if (isset($user) && $user->isLogin() && $user->isAdmin())
					{
						echo '<form method="post">';
						echo  '<label for="permalink">Permanent link:</label>';
						echo  '<input id="permalink" type="text" size="26" value="https://wuwana.com/my-profile-page">';
						echo  '<br>';
						echo  '<label for="insta">Instagram profile:</label>';
						echo  '<input id="insta" type="text" size="25" placeholder="https://instagram.com/username...">';
						echo  '<br>';
						echo  '<label for="whatsapp">WhatsApp number:</label>';
						echo  '<input id="whatsapp" type="text" size="24" placeholder="+34 123 45 67 89"><br>';
						echo  '<br>';
						echo  '<label for="email">Email address:</label>';
						echo  '<input id="email" type="text" size="26" placeholder="me@email.com"><br>';
						echo  '<br>';
						echo  '<label for="website">Website URL:</label>';
						echo  '<input id="website" type="text" size="27" placeholder="https://www.my-website.com">';
						echo  '<br>';
						echo  '<input type="submit" value="Update info sources">';
						echo '</form>';
					}
				?>
			</div>
		</div>
		<div class="ColumnMain">
			<?php
				if ($company->instagram != null)
				{
					echo '<section>';
					echo  '<h2>', sprintf(TEXT[2], $company->name), '</h2>';
					echo  '<div class="Box">';
					echo   '<div class="InstagramInfo">';
					echo    '<h3>', $company->instagram->profileName, '</h3>';
					echo    '<p>', $company->instagram->biography, '<br>', $company->instagram->link, '</p>';
					echo    '<ul>';
					echo     '<li>';
					echo      '<div class="ItemLabel">';
					echo       '<span class="Number">', $company->instagram->instagramNbPost, '</span>';
					echo       '<span class="Text">Posts</span>';
					echo      '</div>';
					echo     '</li>';
					echo     '<li>';
					echo      '<div class="ItemLabel">';
					echo       '<span class="Number">', $company->instagram->instagramNbFollower, '</span>';
					echo       '<span class="Text">Followers</span>';
					echo      '</div>';
					echo     '</li>';
					echo     '<li>';
					echo      '<div class="ItemLabel">';
					echo       '<span class="Number">', $company->instagram->instagramNbFollowing, '</span>';
					echo       '<span class="Text">Following</span>';
					echo      '</div>';
					echo     '</li>';
					echo    '</ul>';
					echo   '</div>';
					echo   '<div class="Aspect2-3">';
					echo    '<div class="InstagramGallery">';
					echo     '<div class="InstagramRow">';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[0], '"></div>';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[1], '"></div>';
					echo     '</div>';
					echo     '<div class="InstagramRow">';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[2], '"></div>';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[3], '"></div>';
					echo     '</div>';
					echo     '<div class="InstagramRow">';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[4], '"></div>';
					echo      '<div class="InstagramPicture"><img src="', $company->instagram->pictures[5], '"></div>';
					echo     '</div>';
					echo    '</div>';
					echo   '</div>';
					echo   '<div class="Button Absolute">';
					echo    '<a href="', $company->instagram->url, '" target="_blank">';
					echo     '<img src="/static/icon/instagram.svg">', TEXT[5];
					echo    '</a>';
					echo   '</div>';
					echo  '</div>';
					echo '</section>';
				}
			?>
			<section>
				<h2><?php echo TEXT[3] ?></h2>
				<div class="Box Test"></div>
			</section>
			<a class="Center" href="/">
				<div class="Button Center"><img src="/static/icon/home.svg"><?php echo TEXT[4] ?></div>
			</a>
		</div>
	</div>
	<?php include '../Templates/page footer.php' ?>
</body>
</html>