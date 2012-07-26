<?php

chdir(dirname(__FILE__));

if (!file_exists('./version.txt')) {
    run_migrations(find_lowest_migration());
} else {
    run_migrations(trim(file_get_contents('./version.txt')) + 1);
}

function run_migrations($mig_id) {

    require '../../init.local.php';

    if (!file_exists('./migrations/' . $mig_id . '.sql')) {
        echo 'At migration ' . ($mig_id-1) . ".\n";
        return;
    }

    while (file_exists('./migrations/' . $mig_id . '.sql')) {
        echo 'Executing migration: ' . $mig_id . "\n";
        $cmd = sprintf('mysql -u%s -p"%s" %s < ./migrations/%d.sql 2>&1', DBUSER, DBPASS, DBNAME, $mig_id);
        $out = trim(shell_exec($cmd));
        if (!empty($out)) {
            echo $out . "\n";
            die('Failed migration: ' . $mig_id . "\n");
        }
        file_put_contents('./version.txt', $mig_id);
        $mig_id++;
    }

}

function find_lowest_migration() {
    $cmd = 'find ./migrations -iname \*.sql | cut -c 14- | sort -n | head -n1';
    preg_match('/\d+/', exec($cmd), $match);
    return $match[0];
}



