const express = require('express');
const router = express.Router();
const busStopController = require('../controllers/bus_stop.controller');
const awaitHandlerFactory = require('../middleware/awaitHandlerFactory.middleware');

router.get('/', awaitHandlerFactory(busStopController.getAllBusStops)); // localhost:8080/api/busStop
router.get('/id/:id', awaitHandlerFactory(busStopController.getBusStopById)); // localhost:8080/api/busStop/id/1
router.get('/name/:name', awaitHandlerFactory(busStopController.getBusStopByName)); // localhost:8080/api/busStop/whoami
router.post('/', awaitHandlerFactory(busStopController.createBusStop)); // localhost:8080/api/busStop
router.put('/id/:id', awaitHandlerFactory(busStopController.updateBusStop)); // localhost:8080/api/busStop/id/1 , using put for partial update
router.delete('/id/:id', awaitHandlerFactory(busStopController.deleteBusStop)); // localhost:8080/api/busStop/id/1

module.exports = router;