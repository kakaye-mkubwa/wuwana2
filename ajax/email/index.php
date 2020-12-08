<?php
/**
 * Async controller for login request by email.
 */

spl_autoload_register(function($className) {
	require '../../Models/' . str_replace('\\', '/', $className) . '.php';
});

if (!filter_has_var(INPUT_POST, 'email'))
{ exit; }

$language = WebApp\WebApp::getLanguageCode();
require 'text ' . $language . '.php';

$dao = WebApp\Data::getUser();
$user = new WebApp\UserSession($dao);
$user->sendEmail(
	filter_input(INPUT_POST, 'email'),
	'Login Wuwana <noreply@wuwana.com>',
	TEXT[0],
	'<body style="font-size:1rem; margin:24px; line-height:1.3; font-family:sans-serif">'
	. '<svg width="150" height="22" viewBox="0 0 150 22" fill="none" xmlns="http://www.w3.org/2000/svg">'
	.  '<path d="M100.989 0.0406478C102.046 0.0406478 102.818 0.121944 103.346 0.284538C103.874 0.447131 104.24 0.731671 104.443 1.13816C104.646 1.54464 104.768 1.91048 104.809 2.23566C104.85 2.56085 104.89 3.04863 104.89 3.65836V18.1698C104.89 18.8202 104.85 19.2674 104.809 19.5925C104.768 19.9177 104.646 20.2836 104.443 20.69C104.077 21.4217 103.143 21.7876 101.639 21.7876C100.176 21.7876 98.0629 20.7307 97.4533 20.7307C96.1935 20.7307 95.0962 21.7876 92.7797 21.7876C90.4227 21.7876 88.2281 20.69 86.2368 18.495C84.2048 16.3 83.2295 13.7798 83.2295 10.8938C83.2295 8.00774 84.2455 5.48754 86.2774 3.29252C88.3094 1.13816 90.5039 0 92.9423 0C93.877 0 94.7304 0.203242 95.4619 0.528429C96.2341 0.853617 97.0469 0.894264 97.4939 0.894264C99.0788 0.934912 98.9976 0.0406478 100.989 0.0406478ZM90.3414 10.9751C90.3414 11.8693 90.7071 12.723 91.398 13.4546C92.0889 14.1863 92.9423 14.5521 93.9583 14.5521C94.9743 14.5521 95.7871 14.1863 96.4779 13.414C97.1282 12.6823 97.4939 11.8287 97.4939 10.9344C97.4939 10.0402 97.1688 9.18654 96.5186 8.41422C95.8683 7.6419 95.0149 7.23542 93.9583 7.23542C92.9017 7.23542 92.0076 7.6419 91.3574 8.41422C90.6665 9.22719 90.3414 10.0808 90.3414 10.9751Z" fill="#161313"/>'
	.  '<path d="M146.099 0.0406478C147.155 0.0406478 147.927 0.121944 148.456 0.284538C148.984 0.447131 149.35 0.731671 149.553 1.13816C149.756 1.54464 149.878 1.91048 149.919 2.23566C149.959 2.56085 150 3.04863 150 3.65836V18.1698C150 18.8202 149.959 19.2674 149.919 19.5925C149.878 19.9177 149.756 20.2836 149.553 20.69C149.187 21.4217 148.252 21.7876 146.749 21.7876C145.286 21.7876 143.173 20.7307 142.563 20.7307C141.303 20.7307 140.206 21.7876 137.889 21.7876C135.532 21.7876 133.338 20.69 131.346 18.495C129.315 16.3 128.339 13.7798 128.339 10.8938C128.339 8.00774 129.355 5.48754 131.387 3.29252C133.419 1.13816 135.614 0 138.052 0C138.987 0 139.84 0.203242 140.572 0.528429C141.344 0.853617 142.157 0.894264 142.604 0.894264C144.148 0.934912 144.107 0.0406478 146.099 0.0406478ZM135.451 10.9751C135.451 11.8693 135.817 12.723 136.508 13.4546C137.199 14.1863 138.052 14.5521 139.068 14.5521C140.084 14.5521 140.897 14.1863 141.588 13.414C142.238 12.6823 142.604 11.8287 142.604 10.9344C142.604 10.0402 142.278 9.18654 141.628 8.41422C140.978 7.6419 140.125 7.23542 139.068 7.23542C138.011 7.23542 137.117 7.6419 136.467 8.41422C135.776 9.22719 135.451 10.0808 135.451 10.9751Z" fill="#161313"/>'
	.  '<path d="M125.494 2.27631C126.714 3.78031 127.323 6.05662 127.323 9.10525V18.3324C127.323 19.43 126.998 20.2836 126.307 20.8933C125.616 21.503 124.722 21.8282 123.544 21.8282C122.365 21.8282 121.471 21.503 120.78 20.8933C120.089 20.2836 119.764 19.43 119.764 18.3324V9.43044C119.764 8.17033 119.561 7.27607 119.114 6.70699C118.667 6.13791 118.017 5.85338 117.123 5.85338C115.985 5.85338 115.091 6.21921 114.441 6.95088C113.79 7.68255 113.425 8.65812 113.425 9.87757V18.3324C113.425 19.43 113.099 20.2836 112.409 20.8933C111.718 21.503 110.824 21.8282 109.645 21.8282C108.467 21.8282 107.573 21.503 106.882 20.8933C106.191 20.2836 105.866 19.43 105.866 18.3324V3.53641C105.866 2.5202 106.231 1.70723 106.922 1.09751C107.613 0.447133 108.548 0.0406494 109.686 0.0406494C110.742 0.0406494 111.636 0.406486 112.246 1.01621C112.896 1.62594 113.668 1.50399 113.872 1.50399C114.075 1.50399 114.888 1.38205 116.066 0.894266C117.651 0.243891 118.505 0.0406494 119.927 0.0406494C122.406 0.0406494 124.275 0.772321 125.494 2.27631Z" fill="#161313"/>'
	.  '<path d="M51.5714 1.05686C52.2622 1.66659 52.5874 2.5202 52.5874 3.61771V18.3324C52.5874 19.308 52.2216 20.121 51.5307 20.7713C50.8399 21.4217 49.9052 21.8282 48.7673 21.8282C47.7513 21.8282 46.8978 21.4624 46.2476 20.9339C45.5974 20.3649 45.3535 20.3242 44.9878 20.3242C44.622 20.3242 43.7686 20.4868 42.7526 21.0152C41.7366 21.5437 40.5987 21.8282 39.2983 21.8282C36.6161 21.8282 34.6247 21.0965 33.3243 19.5925C32.0238 18.0886 31.3736 15.8529 31.3736 12.8043V3.61771C31.3736 2.56085 31.6987 1.70724 32.3896 1.05686C33.0805 0.447134 33.9745 0.0406494 35.1531 0.0406494C36.3316 0.0406494 37.2257 0.447134 37.9165 1.05686C38.6074 1.66659 38.9325 2.5202 38.9325 3.61771V12.9669C38.9325 14.9993 39.8266 16.0155 41.5741 16.0155C42.5901 16.0155 43.4435 15.6496 44.0937 14.918C44.744 14.1863 45.0691 13.2107 45.0691 12.0319V3.61771C45.0691 2.56085 45.3942 1.70724 46.0851 1.05686C46.7759 0.447134 47.67 0.0406494 48.8485 0.0406494C49.9864 0.0406494 50.8805 0.447134 51.5714 1.05686Z" fill="#161313"/>'
	.  '<path d="M30.0731 4.83716C30.2357 4.43068 30.3576 3.98355 30.3576 3.49576C30.3576 1.58529 28.8133 0.0406478 26.9033 0.0406478C25.5215 0.0406478 24.3024 0.853617 23.774 2.03242C23.774 2.03242 23.774 2.07307 23.7334 2.07307C23.6928 2.15437 23.6521 2.23566 23.6521 2.27631C23.2457 3.29252 22.0672 6.5444 21.9859 6.70699C21.8234 7.19477 21.5389 8.25163 20.9699 8.25163C20.5635 8.25163 20.4416 8.08904 19.9946 6.82893C19.6288 5.77208 19.2224 4.63392 18.6128 2.92669L18.2877 2.23566C18.0845 1.78853 17.6781 1.38205 17.1092 0.934914C16.5402 0.487781 15.8494 0.284538 15.0366 0.284538C14.2238 0.284538 13.4516 0.691023 12.6389 1.46334C12.1918 1.86983 11.9073 2.35761 11.7448 2.88604L10.3224 6.82893C10.3224 6.82893 9.83473 8.25163 9.3877 8.25163C8.90003 8.25163 8.69683 7.60125 8.41236 6.82893C7.47765 4.26808 7.03062 2.96734 6.66486 2.15437C6.13655 0.894265 4.91737 0 3.45435 0C1.5443 0 0 1.54464 0 3.45512C0 3.90225 0.0812813 4.34938 0.243839 4.71522L6.25847 19.5925C6.46167 20.0803 6.82742 20.5681 7.39637 21.0152C7.96533 21.503 8.65619 21.7063 9.50962 21.7063C10.363 21.7063 11.0946 21.3811 11.7448 20.7307C11.9886 20.4868 12.1918 20.2023 12.395 19.8771C12.5982 19.5519 12.6795 19.3487 12.7201 19.2267C12.7608 19.1048 12.842 18.8609 13.0046 18.495C13.1672 18.1292 13.4923 17.2349 14.0206 15.7309C14.1425 15.3245 14.3051 14.9586 14.427 14.6334C14.4676 14.5115 14.7521 13.8611 15.2398 13.8611C15.6462 13.8611 15.89 14.3489 15.9713 14.5521C16.906 16.991 17.4343 18.4137 17.5969 18.8609L17.8813 19.5519C18.1252 20.0397 18.4909 20.5275 19.0599 20.9746C19.6288 21.4624 20.2384 21.6656 20.848 21.6656C21.4576 21.6656 21.9859 21.5843 22.3923 21.3811C23.327 20.8933 23.9772 20.0397 24.4243 18.8609L28.854 8.04839C29.0572 7.43866 29.6668 5.85337 30.0731 4.83716Z" fill="#161313"/>'
	.  '<path d="M83.7172 4.83716C83.8798 4.43068 84.0017 3.98355 84.0017 3.49576C84.0017 1.58529 82.4574 0.0406478 80.5473 0.0406478C79.1656 0.0406478 77.9464 0.853617 77.4181 2.03242C77.4181 2.03242 77.4181 2.07307 77.3774 2.07307C77.3368 2.15437 77.2962 2.23566 77.2962 2.27631C76.8898 3.29252 75.7112 6.5444 75.63 6.70699C75.4674 7.19477 75.1829 8.25163 74.614 8.25163C74.2076 8.25163 74.0857 8.08904 73.6386 6.82893C73.2729 5.77208 72.8665 4.63392 72.2569 2.92669L71.9318 2.23566C71.7286 1.78853 71.3222 1.38205 70.7532 0.934914C70.1843 0.487781 69.4934 0.284538 68.6806 0.284538C67.8678 0.284538 67.0957 0.691023 66.2829 1.46334C65.8359 1.86983 65.5514 2.35761 65.3888 2.88604L63.9664 6.82893C63.9664 6.82893 63.4788 8.25163 63.0317 8.25163C62.5441 8.25163 62.3409 7.60125 62.0564 6.82893C61.1217 4.26808 60.6747 2.96734 60.3089 2.15437C59.7806 0.894265 58.5614 0 57.0984 0C55.1883 0 53.644 1.54464 53.644 3.45512C53.644 3.90225 53.7253 4.34938 53.8879 4.71522L59.9025 19.5925C60.1057 20.0803 60.4715 20.5681 61.0404 21.0152C61.6094 21.503 62.3002 21.7063 63.1537 21.7063C64.0071 21.7063 64.7386 21.3811 65.3888 20.7307C65.6327 20.4868 65.8359 20.2023 66.0391 19.8771C66.2423 19.5519 66.3235 19.3487 66.3642 19.2267C66.4048 19.1048 66.4861 18.8609 66.6487 18.495C66.8112 18.1292 67.1363 17.2349 67.6646 15.7309C67.7866 15.3245 67.9491 14.9586 68.071 14.6334C68.1117 14.5115 68.3961 13.8611 68.8838 13.8611C69.2902 13.8611 69.5341 14.3489 69.6153 14.5521C70.55 16.991 71.0784 18.4137 71.2409 18.8609L71.5254 19.5519C71.7692 20.0397 72.135 20.5275 72.7039 20.9746C73.2729 21.4624 73.8825 21.6656 74.4921 21.6656C75.1017 21.6656 75.63 21.5843 76.0364 21.3811C76.9711 20.8933 77.6213 20.0397 78.0683 18.8609L82.498 8.04839C82.7012 7.43866 83.2702 5.85337 83.7172 4.83716Z" fill="#161313"/>'
	. '</svg>'
	. '<br><br>'
	. '<p style="line-height:1.3; margin:8px 0 8px">' . TEXT[1] . '<br>' . TEXT[2] . '</p>'
	. '<span style="font-size:3rem; font-weight:700; letter-spacing:8px; line-height:80px">%s</span>'
	. '<p style="line-height:1.3; margin:8px 0 8px">'
	.  '<span style="font-weight:600">' . TEXT[3] . '</span>'
	.  '<br>'
	.  '<ol style="padding-inline-start:24px; margin:4px 0 16px">'
	.   '<li style="margin-bottom:4px">' . TEXT[4] . '</li>'
	.   '<li style="margin-bottom:4px">' . TEXT[5] . '</li>'
	.   '<li style="margin-bottom:4px">' . TEXT[6] . '</li>'
	.  '</ol>'
	. '</p>'
	. '<p>' . TEXT[7] . '<br>' . TEXT[8] . '</p>'
	. '<hr style="margin-top:40px">'
	. '<p style="line-height:1.2; color:#8B8989; margin: 8px 0 8px">'
	.  TEXT[9]
	. '</p></body>'
);