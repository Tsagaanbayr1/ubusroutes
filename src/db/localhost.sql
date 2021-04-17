-- --------------------------------------------------------
--
-- Table structure for table bus_route
--
CREATE TABLE bus_route (
  id bigint PRIMARY KEY NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
);
-- --------------------------------------------------------
--
-- Table structure for table bus_stop
--
CREATE TABLE bus_stop (
  id bigint PRIMARY KEY NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  latitude double NOT NULL,
  longitude double NOT NULL
);
--
-- --------------------------------------------------------
--
-- Table structure for table bus_relation
--
CREATE TABLE bus_relation (
  id bigint PRIMARY KEY NOT NULL,
  stop_id bigint NOT NULL REFERENCES bus_route (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  route_id bigint NOT NULL,
  seq int NOT NULL,
  turn tinyint NOT NULL DEFAULT '1'
);
--
-- Indexes for dumped tables
--
--
-- Indexes for table bus_relation
--
ALTER TABLE bus_relation
ADD PRIMARY KEY (id),
  ADD KEY bus_stop_fk (stop_id),
  ADD KEY bus_route_fk (route_id);
--
-- Indexes for table bus_route
--
ALTER TABLE bus_route
ADD PRIMARY KEY (id);
--
-- Indexes for table bus_stop
--
ALTER TABLE bus_stop
ADD PRIMARY KEY (id);
ALTER TABLE bus_relation
ADD CONSTRAINT bus_route_fk FOREIGN KEY (route_id) REFERENCES bus_route (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT bus_stop_fk FOREIGN KEY (stop_id) REFERENCES bus_stop (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;