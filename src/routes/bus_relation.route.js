const express = require('express');
const router = express.Router();
const busRelationController = require('../controllers/bus_relation.controller');
const awaitHandlerFactory = require('../middleware/awaitHandlerFactory.middleware');

router.get('/', awaitHandlerFactory(busRelationController.getAllBusRelations)); // localhost:8080/api/busRelation
router.get('/id/:id', awaitHandlerFactory(busRelationController.getBusRelationById)); // localhost:8080/api/busRelation/id/1
router.get('/name/:name', awaitHandlerFactory(busRelationController.getBusRelationByName)); // localhost:8080/api/busRelation/whoami
router.post('/', awaitHandlerFactory(busRelationController.createBusRelation)); // localhost:8080/api/busRelation
router.put('/id/:id', awaitHandlerFactory(busRelationController.updateBusRelation)); // localhost:8080/api/busRelation/id/1 , using put for partial update
router.delete('/id/:id', awaitHandlerFactory(busRelationController.deleteBusRelation)); // localhost:8080/api/busRelation/id/1

module.exports = router;