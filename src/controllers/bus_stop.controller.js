<<<<<<< HEAD
const BusStopModel = require('../models/bus_stop.model');
const HttpException = require('../utils/HttpException.utils');
const dotenv = require('dotenv');
dotenv.config();

/******************************************************************************
 *                              BusStop Controller
 ******************************************************************************/
class BusStopController {
    async getAllBusStops(req, res, next) {
        let busStopList = await BusStopModel.find();
        if (!busStopList.length) {
            throw new HttpException(404, 'BusStops not found');
        }

        busStopList = busStopList.map(busStop => {
            const {
                ...busStopWithoutPassword
            } = busStop;
            return busStopWithoutPassword;
        });

        res.send(busStopList);
    }

    async getAll() {

    }

    async getBusStopById(req, res, next) {
        const busStop = await BusStopModel.findOne({
            code: req.params.id
        });
        if (!busStop) {
            throw new HttpException(404, 'BusStop not found');
        }

        const {
            ...busStopWithoutPassword
        } = busStop;

        res.send(busStopWithoutPassword);
    }

    async getBusStopByName(req, res, next) {
        const busStop = await BusStopModel.findOne({
            name: req.params.name
        });
        if (!busStop) {
            throw new HttpException(404, 'BusStop not found');
        }

        const {
            ...busStopWithoutPassword
        } = busStop;

        res.send(busStopWithoutPassword);
    }

    async createBusStop(req, res, next) {
        const result = await BusStopModel.create(req.body);

        if (!result) {
            throw new HttpException(500, 'Something went wrong');
        }

        res.status(201).send('BusStop was created!');
    }

    async updateBusStop(req, res, next) {
        const {
            ...restOfUpdates
        } = req.body;

        // do the update query and get the result
        // it can be partial edit
        const result = await BusStopModel.update(restOfUpdates, req.params.id);

        if (!result) {
            throw new HttpException(404, 'Something went wrong');
        }

        const {
            affectedRows,
            changedRows,
            info
        } = result;

        const message = !affectedRows ? 'BusStop not found' :
            affectedRows && changedRows ? 'BusStop updated successfully' : 'Updated field';

        res.send({
            message,
            info
        });
    }

    async deleteBusStop(req, res, next) {
        const result = await BusStopModel.delete(req.params.id);
        if (!result) {
            throw new HttpException(404, 'BusStop not found');
        }
        res.send('BusStop has been deleted');
    }
}



/******************************************************************************
 *                               Export
 ******************************************************************************/
=======
const BusStopModel = require('../models/bus_stop.model');
const HttpException = require('../utils/HttpException.utils');
const dotenv = require('dotenv');
dotenv.config();

/******************************************************************************
 *                              BusStop Controller
 ******************************************************************************/
class BusStopController {
    async getAllBusStops(req, res, next) {
        let busStopList = await BusStopModel.find();
        if (!busStopList.length) {
            throw new HttpException(404, 'BusStops not found');
        }

        busStopList = busStopList.map(busStop => {
            const {
                ...busStopWithoutPassword
            } = busStop;
            return busStopWithoutPassword;
        });

        res.send(busStopList);
    }

    async getAll() {

    }

    async getBusStopById(req, res, next) {
        const busStop = await BusStopModel.findOne({
            code: req.params.id
        });
        if (!busStop) {
            throw new HttpException(404, 'BusStop not found');
        }

        const {
            ...busStopWithoutPassword
        } = busStop;

        res.send(busStopWithoutPassword);
    }

    async getBusStopByName(req, res, next) {
        const busStop = await BusStopModel.findOne({
            name: req.params.name
        });
        if (!busStop) {
            throw new HttpException(404, 'BusStop not found');
        }

        const {
            ...busStopWithoutPassword
        } = busStop;

        res.send(busStopWithoutPassword);
    }

    async createBusStop(req, res, next) {
        const result = await BusStopModel.create(req.body);

        if (!result) {
            throw new HttpException(500, 'Something went wrong');
        }

        res.status(201).send('BusStop was created!');
    }

    async updateBusStop(req, res, next) {
        const {
            ...restOfUpdates
        } = req.body;

        // do the update query and get the result
        // it can be partial edit
        const result = await BusStopModel.update(restOfUpdates, req.params.id);

        if (!result) {
            throw new HttpException(404, 'Something went wrong');
        }

        const {
            affectedRows,
            changedRows,
            info
        } = result;

        const message = !affectedRows ? 'BusStop not found' :
            affectedRows && changedRows ? 'BusStop updated successfully' : 'Updated field';

        res.send({
            message,
            info
        });
    }

    async deleteBusStop(req, res, next) {
        const result = await BusStopModel.delete(req.params.id);
        if (!result) {
            throw new HttpException(404, 'BusStop not found');
        }
        res.send('BusStop has been deleted');
    }
}



/******************************************************************************
 *                               Export
 ******************************************************************************/
>>>>>>> 9d5afe21741e7f4a1bc74b12b2afe87525af79a9
module.exports = new BusStopController;