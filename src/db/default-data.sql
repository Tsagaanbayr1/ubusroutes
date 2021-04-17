/* Bus stop */
TRUNCATE `bus_stop`;
INSERT INTO `bus_stop` (`id`, `name`, `latitude`, `longitude`)
VALUES (`1`, `Test 1`, `0.561465`, `0.00654`),
    (`2`, `Test 2`, `0.0061`, `0.00045`);
/* Bus route */
TRUNCATE `bus_route`;
INSERT INTO `bus_route` (`id`, `name`)
VALUES (`1`, `Test 1`),
    (`2`, `Test 2`);
/* Bus relation */
TRUNCATE `bus_relation`;
INSERT INTO `bus_relation` (`bus_id`, `route_id`, `seq`, `turn`)
VALUES (`1`, `1`, `1`, `false`),
    (`2`, `2`, `1`, `true`);