For run app write command composer update and docker-compose up in cmd.

This app get 3 files (start log, end log, abbreviations description) from 
report of qualification in Monaco 2018, and make consolidated report.

App working as browser application and as console application.

For run console app try to write next commands

- php bin/console app:report --files resources
- php bin/console app:report --files resources -s ASC
- php bin/console app:report --files resources --sort_order DESC
- php bin/console app:report --files resources --driver "Sergey Sirotkin"(or choose another driver)
- php bin/console app:report --driver "Sebastian Vettel" --files resources -s DESC