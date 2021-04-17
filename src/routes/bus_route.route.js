const express = require('express');
const router = express.Router();
const busRouteController = require('../controllers/bus_route.controller');
const awaitHandlerFactory = require('../middleware/awaitHandlerFactory.middleware');

router.get('/', awaitHandlerFactory(busRouteController.getAllBusRoutes)); // localhost:8080/api/busRoute
router.get('/id/:id', awaitHandlerFactory(busRouteController.getBusRouteById)); // localhost:8080/api/busRoute/id/1
router.get('/name/:name', awaitHandlerFactory(busRouteController.getBusRouteByName)); // localhost:8080/api/busRoute/whoami
router.post('/', awaitHandlerFactory(busRouteController.createBusRoute)); // localhost:8080/api/busRoute
router.put('/id/:id', awaitHandlerFactory(busRouteController.updateBusRoute)); // localhost:8080/api/busRoute/id/1 , using put for partial update
router.delete('/id/:id', awaitHandlerFactory(busRouteController.deleteBusRoute)); // localhost:8080/api/busRoute/id/1

module.exports = router;