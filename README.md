# plugin_geo
plugin for creating new post type and using google maps api
===========================================================

This plugin is written for WordPress.<br>
This plugin uses API Google Maps.<br>
This plugin creates a custom post type called "Experts" with a custom field to enter the expert's address.<br>
After that, this plugin creates the [expertsmap] shortcode to display a table of experts and a google map with expert markers corresponding to the addresses.<br>
<hr>
Default parameters:<br>
google-maps-api-key = XXXXXXXXXXXXX
width-map = 640px  // map width<br>
height-map = 400px // map height<br>
width-table = 640px // table width<br>
quantity =all // Number of experts in the table and on the map - all<br>
<hr>
You can change these parameters.<br>
For example:<br>
[expertsmap quantity=2]  // Number of experts in the table and on the map - 2<br>
[expertsmap width-table=400px]  // table width- 400px<br>
<hr>
If there are no custom posts, then the shortcode displays several example experts with addresses.<br>
See the link for an example of how the plugin works:http://geo.denistpro.bg.cm/52-2/,<br>
The project is in working condition, but some changes can be made until 09/26/2022<br>
