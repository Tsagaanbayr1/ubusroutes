-- --------------------------------------------------------
--
-- Table structure for table bus_route
--
DROP TABLE IF EXISTS `bus_route`;
CREATE TABLE `bus_route` (
    `id` bigint PRIMARY KEY NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
);
-- --------------------------------------------------------
--
-- Table structure for table bus_stop
--
DROP TABLE IF EXISTS `bus_stop`;
CREATE TABLE `bus_stop` (
    `id` bigint PRIMARY KEY NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `latitude` double NOT NULL,
    `longitude` double NOT NULL
);
--
-- --------------------------------------------------------
--
-- Table structure for table bus_relation
--
DROP TABLE IF EXISTS `bus_relation`;
CREATE TABLE `bus_relation` (
    `id` bigint PRIMARY KEY AUTO_INCREMENT,
    `stop_id` bigint NOT NULL REFERENCES bus_stop (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    `route_id` bigint NOT NULL REFERENCES bus_route(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    `seq` int NOT NULL,
    `turn` BOOLEAN NOT NULL DEFAULT true
);