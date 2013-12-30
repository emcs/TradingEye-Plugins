Install Instructions

1) Extract the folder inside the archive to the Plugins folder of your Tradingeye install

2) Edit bannerConfig.php with your preferred text editor. Its located in the root bannerManager folder.
On Line 23 it says$bannerConfig['IMAGE-DIR'] = "/images/banners/"; If Tradingeye is installed in a subfolder you need to add the subfolder to this filepath.
Ie: $bannerConfig['IMAGE-DIR'] = "/TradingEye/images/banners/"; You should also take notice of the last few lines of the config file. They currently look like this:
$bannerConfig['BANNER-PLACEHOLDERS'][0] = "468x60";
$bannerConfig['BANNER-PLACEHOLDERS'][1] = "160x600";
$bannerConfig['BANNER-PLACEHOLDERS'][2] = "300x250";
The 468x60,160x600, and 300x250 are the variables to use to identify in your templates where you want a banner at. Ie: {468x60} You can also type this variable into the content editors.

4) Navigate to your root install of Tradingeye and open the images folder. Inside create a folder called banners. Set its permissions to 0777.
