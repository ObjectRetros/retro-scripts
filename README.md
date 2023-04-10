# Retro scripts

The scripts inside this repository is purely script I once had use for, that also could be useful to the public, to ease the process of improving your hotel.

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


**Important**

Make sure to always backup your database before running scripts, in-case something unforseen happens - Using any of the script is on your own responsibility.

**Contributions**

Any contributions is much appreciated, so in-case you have a script you think that'd be useful to the public, feel free to create a pull request, explaining what your script does, and why it's useful.

**Disclaimer**
Those scripts is for educational purpose only. I am not responsible for how or where those scripts are being used
