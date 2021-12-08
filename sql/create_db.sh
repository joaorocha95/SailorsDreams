#!/usr/bin/env bash

echo "Initializing Postgres table creation"
echo "Wish to proceed? [Y/n]"
read choice
if [ $choice = "Y" ] || [ $choice = "y" ] || [ $choice = "yes" ] ; then
    echo "Reunning..."
    command psql --host=db.fe.up.pt --dbname=lbaw2182 --user=lbaw2182 -f schema.sql
    command psql --host=db.fe.up.pt --dbname=lbaw2182 --user=lbaw2182 -f triggers.sql
    command psql --host=db.fe.up.pt --dbname=lbaw2182 --user=lbaw2182 -f index.sql
    command psql --host=db.fe.up.pt --dbname=lbaw2182 --user=lbaw2182 -f populate.sql
    else
        echo "Invalid Option"
        exit
fi
echo "Completed"
