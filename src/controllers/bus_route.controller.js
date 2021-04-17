const BusRouteModel = require('../models/bus_route.model');
const HttpException = require('../utils/HttpException.utils');
const dotenv = require('dotenv');
dotenv.config();

/******************************************************************************
 *                              BusRoute Controller
 ******************************************************************************/
class BusRouteController {
    getAllBusRoutes = async (req, res, next) => {
        let busRouteList = await BusRouteModel.find();
        if (!busRouteList.length) {
            throw new HttpException(404, 'BusRoutes not found');
        }

        busRouteList = busRouteList.map(busRoute => {
            const {
                ...busRouteWithoutPassword
            } = busRoute;
            return busRouteWithoutPassword;
        });

        res.send(busRouteList);
    };

    getBusRouteById = async (req, res, next) => {
        const busRoute = await BusRouteModel.findOne({
            code: req.params.id
        });
        if (!busRoute) {
            throw new HttpException(404, 'BusRoute not found');
        }

        const {
            ...busRouteWithoutPassword
        } = busRoute;

        res.send(busRouteWithoutPassword);
    };

    getBusRouteByName = async (req, res, next) => {
        const busRoute = await BusRouteModel.findOne({
            name: req.params.name
        });
        if (!busRoute) {
            throw new HttpException(404, 'BusRoute not found');
        }

        const {
            ...busRouteWithoutPassword
        } = busRoute;

        res.send(busRouteWithoutPassword);
    };

    createBusRoute = async (req, res, next) => {
        const result = await BusRouteModel.create(req.body);

        if (!result) {
            throw new HttpException(500, 'Something went wrong');
        }

        res.status(201).send('BusRoute was created!');
    };

    updateBusRoute = async (req, res, next) => {
        const {
            ...restOfUpdates
        } = req.body;

        // do the update query and get the result
        // it can be partial edit
        const result = await BusRouteModel.update(restOfUpdates, req.params.id);

        if (!result) {
            throw new HttpException(404, 'Something went wrong');
        }

        const {
            affectedRows,
            changedRows,
            info
        } = result;

        const message = !affectedRows ? 'BusRoute not found' :
            affectedRows && changedRows ? 'BusRoute updated successfully' : 'Updated field';

        res.send({
            message,
            info
        });
    };

    deleteBusRoute = async (req, res, next) => {
        const result = await BusRouteModel.delete(req.params.id);
        if (!result) {
            throw new HttpException(404, 'BusRoute not found');
        }
        res.send('BusRoute has been deleted');
    };
}



/******************************************************************************
 *                               Export
 ******************************************************************************/
module.exports = new BusRouteController;