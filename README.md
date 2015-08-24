# tream
A multi client portfolio management system for external asset manager

Install
-------

1. Basis is a standard LAMP server (https://wiki.debian.org/LaMp)
2. Download and install p4a (https://github.com/fballiano/p4a)
3. Download and install pChart (http://www.pchart.net/)
4. Download all TREAM files and save it in the www root folder incl. the path (e.g. /batch in ../www/batch/.. )
5. Create a new sql database with a user called p4a and save the password in /batch/tream_db_adapter.php and /p4a/applications/tream/index.php
6. create the database by executing /sql/tream.sql
7. fill the database with default settings by executing /sql/tream_data.sql
8. start the program with /yourserver/p4a/applications/tream

Folder structure
----------------

/batch - the php code in this folder is used for the backend processing either for scheduled batch jobs or for the gui
/p4a/applications/tream - the folder tream and the files below it should be dropped into the /p4a/applications folder created by p4a
/sql - these sql files are just needed to create the database before the first start
