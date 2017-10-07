rm *.zip
cp -r /Users/jennifer/sites/nodwell.net/gw2crafting/administrator/components/com_gw2crafter/* com_gw2crafter/administrator
cp -r /Users/jennifer/sites/nodwell.net/gw2crafting/components/com_gw2crafter/* com_gw2crafter/site
cp -r /Users/jennifer/sites/nodwell.net/gw2crafting/media/com_gw2crafter/* com_gw2crafter/media
cp -r /Users/jennifer/sites/nodwell.net/gw2crafting/cli/update_gw2_api.php com_gw2crafter/cli/update_gw2_api.php
cp /Users/jennifer/sites/nodwell.net/gw2crafting/administrator/components/com_gw2crafter/gw2crafter.xml com_gw2crafter/gw2crafter.xml
zip -r com_gw2crafter.zip com_gw2crafter
