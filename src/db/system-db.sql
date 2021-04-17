-- Bus Stops
DROP TABLE IF EXISTS `bus_stop`;
CREATE TABLE `bus_stop` (
  `id` BIGINT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `latitude` DOUBLE NOT NULL,
  `longitude` DOUBLE NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
-- Bus Routes
DROP TABLE IF EXISTS `bus_route`;
CREATE TABLE `bus_route` (
  `id` BIGINT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
-- Bus Relations
DROP TABLE IF EXISTS `bus_relation`;
CREATE TABLE `bus_relation` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `stop_id` BIGINT NOT NULL,
  `route_id` BIGINT NOT NULL,
  `seq` INT NOT NULL,
  `turn` BOOLEAN DEFAULT true
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
-- Foreign keys
ALTER TABLE `bus_relation`
ADD FOREIGN KEY (`stop_id`) REFERENCES `bus_stop` (`id`);
ALTER TABLE `bus_relation`
ADD FOREIGN KEY (`route_id`) REFERENCES `bus_route` (`id`);