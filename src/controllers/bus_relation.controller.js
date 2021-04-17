const BusRelationModel = require('../models/bus_relation.model');
const HttpException = require('../utils/HttpException.utils');
const dotenv = require('dotenv');
dotenv.config();

/******************************************************************************
 *                              BusRelation Controller
 ******************************************************************************/
class BusRelationController {
    async getAllBusRelations(req, res, next) {
        let busRelationList = await BusRelationModel.find();
        if (!busRelationList.length) {
            throw new HttpException(404, 'BusRelations not found');
        }

        busRelationList = busRelationList.map(busRelation => {
            const {
                ...busRelationWithoutPassword
            } = busRelation;
            return busRelationWithoutPassword;
        });

        res.send(busRelationList);
    };

    async getBusRelationById(req, res, next) {
        const busRelation = await BusRelationModel.findOne({
            code: req.params.id
        });
        if (!busRelation) {
            throw new HttpException(404, 'BusRelation not found');
        }

        const {
            ...busRelationWithoutPassword
        } = busRelation;

        res.send(busRelationWithoutPassword);
    };

    async getBusRelationByName(req, res, next) {
        const busRelation = await BusRelationModel.findOne({
            name: req.params.name
        });
        if (!busRelation) {
            throw new HttpException(404, 'BusRelation not found');
        }

        const {
            ...busRelationWithoutPassword
        } = busRelation;

        res.send(busRelationWithoutPassword);
    };

    async createBusRelation(req, res, next) {
        const result = await BusRelationModel.create(req.body);

        if (!result) {
            throw new HttpException(500, 'Something went wrong');
        }

        res.status(201).send('BusRelation was created!');
    };

    async updateBusRelation(req, res, next) {
        const {
            ...restOfUpdates
        } = req.body;

        // do the update query and get the result
        // it can be partial edit
        const result = await BusRelationModel.update(restOfUpdates, req.params.id);

        if (!result) {
            throw new HttpException(404, 'Something went wrong');
        }

        const {
            affectedRows,
            changedRows,
            info
        } = result;

        const message = !affectedRows ? 'BusRelation not found' :
            affectedRows && changedRows ? 'BusRelation updated successfully' : 'Updated field';

        res.send({
            message,
            info
        });
    };

    async deleteBusRelation(req, res, next) {
        const result = await BusRelationModel.delete(req.params.id);
        if (!result) {
            throw new HttpException(404, 'BusRelation not found');
        }
        res.send('BusRelation has been deleted');
    };
}



/******************************************************************************
 *                               Export
 ******************************************************************************/
module.exports = new BusRelationController;