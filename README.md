### 🙏 Donations
If you wish to support my work, you can click the button below to "buy me a coffee" it's also possible to create a "subscription" where you'd support me with a coffee every month

<a href="https://www.buymeacoffee.com/dennisobject" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" ></a>


### 📢 Disclaimer

Please note that the files is provided as an educational resource for learning purposes only. The creators and contributors to those files are not responsible for any misuse or unintended consequences arising from the use of the files. By using those files, you agree to take full responsibility for your actions and any consequences resulting from your use of the files. It is your responsibility to ensure that you are using the files in compliance with all applicable laws and regulations.

**Future scripts**

More scripts will potentially be added in the future.


**Catalog page sorter**:

The catalog page sorter script, will sort your catalog in alphabetical order, while allowing you to specify what pages to exclude from the sorting.

You can choose to either run the .SQL version or the PHP version, the outcome will be the same.

**Running the PHP version**

To run the PHP script, simply download or clone the file, edit the database credentials to match your database, once the database credentials has been setup, you open your CMD (Command prompt) browse into the folder your file is stored at and then run ``php catalog-page-sorter.php``. The script will then run and let you know once it's finished.

After the script has run, head into your hotel and do ``:update_catalog``

**Furniture fixer**

The furniture fixer will loop through Habbos furnidata and update the width, length, can_sit, can_walk & can_sit entries for each furniture it can matchup against (room items only).

To run the PHP script, simply download or clone the file, edit the database credentials to match your database, once the database credentials has been setup, you open your CMD (Command prompt) browse into the folder your file is stored at and then run ``php furniture-fixer.php``. The script will then run and let you know once it's finished.

After the script has run, head into your hotel and do ``:update_items`` and potentially reload  the active rooms to reload their data.

**Furnidata translator**

The furnidata translator will loop through the provided furnidata url and update the name and description entries for each furniture it can matchup against in your local furnidata.

To run the PHP script, simply download or clone the file, edit the database credentials to match your database, once the database credentials has been setup, you open your CMD (Command prompt) browse into the folder your file is stored at and then run ``php furnidata-translator-json.php`` or ``php furnidata-translator-xml.php``. The script will then run through the entire furnidata updating any name and description where it can find a match, based on the classname. Before running the script will create a local backup of your local furnidata, in-case you need to reference or restore it. The script will let you know once it's finished.

After the script has run, replace your furnidata with the updated one and then clear cache & reload.


**Import cloudflare IPs script**

This script will import all the IPs from https://www.cloudflare.com/ips-v4 & https://www.cloudflare.com/ips-v6 both to your IIS and to your firewall, this will ensure that only traffic proxied through cloudflare will be allowed - This essentially helps a bit in regards to DDoS attacks.

To run the script do the following:
1. Open powershell as administrator (right click it and then select "Run as Administrator")
2. ``cd`` into the folder where your script is located
3. Execute it by running ``./Import-CloudflareIPs.ps1``
4. Once it's finished open IIS & advanced firewall & security to verify the IPs has been imported correctly
5. In IIS domain & restriction click on ``Edit Feature Settings...`` set ``Access for unspecified clients`` to ``Deny``, toggle ``Enable domain name restrictions`` and then lastly ``Deny Action type`` should be set to ``Abort``, then click "ok"
6. Restart IIS 

**Important**

Make sure to always backup your database before running scripts, in-case something unforseen happens - Using any of the script is on your own responsibility.

**Contributions**

Any contributions is much appreciated, so in-case you have a script you think that'd be useful to the public, feel free to create a pull request, explaining what your script does, and why it's useful.
