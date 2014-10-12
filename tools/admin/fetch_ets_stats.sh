#!/bin/bash

HAS_OPT=true
fetch=true
evaluate=true
while $HAS_OPT 
do
  case $1 in
    -h) echo "\
usage: `basename $0` [options]
options:
  -h    this help
  -f    only fetch the data from db as xml files
  -e    only evaluate the xml files" ; exit;;
    -f) evaluate=false; shift ;;
    -e) fetch=false; shift ;;
    *)  HAS_OPT=false;;
  esac
done


version="99"
db="ETS${version}"
outdb="ETS99"
prefix="ets${version}"
outdir="ets_buildings"

script_buildings="reformat_buildings.vim"
sql="mysql"
sql_pwd=""
sql_args="$db -u root -p${sql_pwd} -X --default-character-set=utf8 -e"
#sql_args="$db -X -e"

cm_gain=3
airport_gain=5
hangar_gain=10

#"b_shield"
buildings=(
"b_iridium_mine"
"b_holzium_plantage"
"b_water_derrick"
"b_oxygen_reactor"
"b_depot"
"b_oxygen_depot"
"b_hangar"
"b_airport"
"b_work_board"
"b_technologie_center"
"b_communication_center"
"b_trade_center"
"b_defense_center"
)

#"t_shield_tech"
techs=(
"t_oxidationsdrive"
"t_hoverdrive"
"t_antigravitydrive"
"t_electronsequenzweapons"
"t_protonsequenzweapons"
"t_neutronsequenzweapons"
"t_consumption_reduction"
"t_plane_size"
"t_computer_management"
"t_depot_management"
"t_water_compression"
"t_mining"
)
specials=(
"iri_to_holzi"
"max_fleet_actual"
"max_fleet_academic"
"hangar_slots"
)
#"max_bomber"

special_queries=(
"SELECT user as id, CAST(b_holzium_plantage AS SIGNED)-CAST(b_iridium_mine AS SIGNED) AS amount FROM city HAVING amount < 1000 ORDER BY amount DESC LIMIT 0,10"
"SELECT c.user as id, LEAST((c.b_airport*${airport_gain})+(u.t_computer_management*${cm_gain}),c.b_hangar*${hangar_gain}) AS amount FROM city as c, usarios as u WHERE c.user=u.user ORDER BY amount DESC LIMIT 0,10"
"SELECT c.user as id, (c.b_airport*${airport_gain})+(u.t_computer_management*${cm_gain}) AS amount FROM city as c, usarios as u WHERE c.user=u.user ORDER BY amount DESC LIMIT 0,10"
"SELECT user as id, b_hangar*${hangar_gain} AS amount FROM city ORDER BY amount DESC LIMIT 0,10"
)
#"SELECT user AS id, sum(p_bomber) AS amount FROM city WHERE p_bomber>0 GROUP BY user ORDER BY sum(p_bomber) DESC, user LIMIT 0,10"

#"Schutzschild"
#"Grösste Bomber-Flotte"
titles=(
"Iridium-Mine"
"Holzium-Plantage"
"Wasser-Bohrturm"
"Sauerstoff-Reaktor"
"Lager"
"Tank"
"Hangar"
"Flughafen"
"Bauzentrum"
"Technologiezentrum"
"Kommunikationszentrum"
"Handelszentrum"
"Verteidigungszentrum"
"Oxidationsantrieb"
"Hoverantrieb"
"Antigravitationsantrieb"
"Elektronensequenzwaffen"
"Protonensequenzwaffen"
"Neutronensequenzwaffen"
"Treibstoffverbrauch-Reduktion"
"Flugzeugkapazitätsverwaltung"
"Computermanagement"
"Lagerverwaltung"
"Wasserkompression"
"Bergbautechnik"
"Iridium-Mine zu Holzium-Plantage"
"Effektiv grösste Flotte"
"Theoretisch grösste Flotte"
"Hangarplätze"
)

target_upgradings="${prefix}_upgradings.xml"

if $fetch
then

echo "creating output folder"

mkdir -p $outdir
if [ ! -d $outdir ]
then
echo "Could not access output folder: "`pwd`"/$outdir"
exit
fi

echo "exporting buildings data"
for building in ${buildings[@]}
do
#    echo $building
    ${sql} ${sql_args} "SELECT user as id, $building as amount FROM city ORDER BY $building DESC LIMIT 0,10" > $outdir/$building.xml
done

echo "exporting techs data"
for tech in ${techs[@]}
do
    ${sql} ${sql_args} "SELECT user as id, $tech as amount FROM usarios ORDER BY amount DESC LIMIT
    0,10" > $outdir/$tech.xml
done

echo "exporting special data"
i=0
for special in ${specials[@]}
do
    ${sql} ${sql_args} "${special_queries[$i]}" > $outdir/$special.xml
    let "i=$i+1"
done

fi #fetching data

if $evaluate
then

cat > ${script_buildings} <<EOF
:1s/<?xml version="1.0"?>/
:%s/\/row/\/value
:%s/<row/<value
:%s/column name="\([^"]*\)"\(>[^<]*<\)\/column/\1\2\/\1
:%s/field name="\([^"]*\)"\(>[^<]*<\)\/field/\1\2\/\1
:%s/\n\n/\r
:%s/<resultset statement="\(.*\)/<expansion>\r<id>__title__<\/id>\r<sql>\1<\/sql>
:%s/" xmlns:xsi="http:\/\/www\.w3\.org\/2001\/XMLSchema-instance">/<values>
:%s/<\/resultset>/<\/values>\r<\/expansion>
:x
EOF


echo "reformatting buildings and tech data"

i=0
#for f in `/bin/ls $outdir/*.xml`
for f in ${buildings[@]} ${techs[@]} ${specials[@]}
do
    vim -s $script_buildings ${outdir}/$f.xml
    vim -c ":%s/<id>__title__<\/id>/<id>${titles[$i]}<\/id>" -c ":x" ${outdir}/$f.xml
    let "i=$i+1"
done

echo "concatenating all files"

echo '<?xml version="1.0" encoding="utf-8" ?>' > $target_upgradings
echo "<${outdb}>" >> $target_upgradings
echo "  <expansions>" >> $target_upgradings
for f in ${buildings[@]} ${techs[@]} ${specials[@]}
do
    `cat ${outdir}/$f.xml >> $target_upgradings`
done
echo "  </expansions>" >> $target_upgradings
echo "</${outdb}>" >> $target_upgradings

fi



if $fetch; then
${sql} ${sql_args} "SELECT tag, members, fame FROM alliances WHERE fame>0 ORDER BY fame DESC LIMIT 0,50" > ${prefix}_alliances_fame.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#alliances#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_alliances_fame.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT tag, members, power FROM alliances WHERE power>0 ORDER BY power DESC LIMIT 0,50" > ${prefix}_alliances_power.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#alliances#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_alliances_power.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT tag, members, points FROM alliances WHERE points>0 ORDER By points DESC LIMIT 0,50" > ${prefix}_alliances_score.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#alliances#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_alliances_score.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT city, city_name, user, points, alliance, home, b_hangar FROM city Order by points desc limit 0,50" > ${prefix}_cities.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#city#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_cities.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT user, type, sum(amount) as amount FROM donations group by user order by amount  DESC LIMIT 0,10000" > ${prefix}_donations.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#donations#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_donations.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT 'Flugzeuge gesamt' as id, FORMAT(SUM(p_gesamt_flugzeuge),0) as amount FROM city
UNION
SELECT 'Defensivanlagen gesamt' as id, FORMAT(SUM(d_electronwoofer+d_protonwoofer+d_neutronwoofer+d_electronsequenzer+d_protonsequenzer+d_neutronsequenzer),0) AS amount FROM city
UNION
SELECT 'Bauaufträge Flugzeuge gesamt' as id, FORMAT(max(id),0) as amount FROM jobs_planes
UNION
SELECT 'Bauaufträge Defensive gesamt' as id, FORMAT(max(id),0) as amount FROM jobs_defense
UNION
SELECT 'Ingame Nachrichten gesamt' as id, FORMAT(max(id),0) as amount FROM news_igm_umid
UNION
SELECT 'Ereignisnachrichten gesamt' as id, FORMAT(max(id),0) as amount FROM news_er
UNION
SELECT 'Berichte gesamt' as id, FORMAT(max(id),0) as amount FROM news_ber
UNION
SELECT 'Siedler am Rundenende' as id, FORMAT(count(*),0) as amount FROM userdata ORDER BY id ASC" | grep -v SELECT | grep -v UNION > ${prefix}_statistics.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}><statistics>#
:%s#</resultset>#</statistics></${outdb}>#
:%s#row#stat#
:%s#field name="id"\(>[^<]*<\)/field#id\1/id#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#values><value><id>dummy</id><\1\2/\1></value></values#
:x
EOF
vim -s vim_script.vim ${prefix}_statistics.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT user, fame, alliance FROM usarios WHERE fame>0 ORDER BY fame DESC LIMIT 0,50" > ${prefix}_users_fame.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#usarios#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_users_fame.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT user, power, alliance FROM usarios WHERE power>0 ORDER BY power DESC LIMIT 0,50" > ${prefix}_users_power.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#usarios#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_users_power.xml
fi



if $fetch; then
${sql} ${sql_args} "SELECT user, points, alliance FROM usarios WHERE points>0 ORDER BY points DESC LIMIT 0,50" > ${prefix}_users_score.xml
fi
if $evaluate; then
cat > vim_script.vim <<EOF
:%s#<resultset statement=".*##
:%s#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">#<${outdb}>#
:%s#</resultset>#</${outdb}>#
:%s#row#usarios#
:%s#field name="\([^"]*\)"\(>[^<]*<\)/field#\1\2/\1#
:x
EOF
vim -s vim_script.vim ${prefix}_users_score.xml
fi

rm -f ${script_buildings}
rm -f vim_script.vim

echo done
