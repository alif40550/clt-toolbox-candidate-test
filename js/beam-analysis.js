'use strict';

/** ============================ Converter Helper ============================ */

/**
 * Converter Helper to convert values.
 *
 * @param {String} name     Material name
 * @param {Number} amount   Material amount
 * @param {Number} unit     Material unit
 */
class UnitConverter {
    constructor(name, amount, unit){
        this.name = name,
        this.amount = amount,
        this.unit = unit
    }

    convertEItoKnm2() {
        if(!this.name == "EI"){
            throw new Error('Name not matches');
        }
        if(!this.unit == 'Knm2'){
            throw new Error('unit already in Knm2');
        }

        return this.amount/(Math.pow(1000, 3));
    }
}

/** ============================ Beam Analysis Data Type ============================ */

/**
 * Beam material specification.
 *
 * @param {String} name         Material name
 * @param {Object} properties   Material properties {EI : 0, GA : 0, ....}
 */
class Material {
    constructor(name, properties) {
        this.name = name;
        this.properties = properties;
    }
}

/**
 *
 * @param {Number} primarySpan          Beam primary span length
 * @param {Number} secondarySpan        Beam secondary span length
 * @param {Material} material           Beam material object
 */
class Beam {
    constructor(primarySpan, secondarySpan, material) {
        this.primarySpan = primarySpan;
        this.secondarySpan = secondarySpan;
        this.material = material;
    }
}

/** ============================ Beam Analysis Class ============================ */

class BeamAnalysis {
    constructor() {
        this.options = {
            condition: 'simply-supported'
        };

        this.analyzer = {
            'simply-supported': new BeamAnalysis.analyzer.simplySupported(),
            'two-span-unequal': new BeamAnalysis.analyzer.twoSpanUnequal()
        };
    }
    /**
     *
     * @param {Beam} beam
     * @param {Number} load
     */
    getDeflection(beam, load, condition) {
        var analyzer = this.analyzer[condition];

        if (analyzer) {
            return {
                beam: beam,
                load: load,
                equation: analyzer.getDeflectionEquation(beam, load)
            };
        } else {
            throw new Error('Invalid condition');
        }
    }
    getBendingMoment(beam, load, condition) {
        var analyzer = this.analyzer[condition];

        if (analyzer) {
            return {
                beam: beam,
                load: load,
                equation: analyzer.getBendingMomentEquation(beam, load)
            };
        } else {
            throw new Error('Invalid condition');
        }
    }  
    getShearForce(beam, load, condition) {
        var analyzer = this.analyzer[condition];

        if (analyzer) {
            return {
                beam: beam,
                load: load,
                equation: analyzer.getShearForceEquation(beam, load)
            };
        } else {
            throw new Error('Invalid condition');
        }
    }
}




/** ============================ Beam Analysis Analyzer ============================ */

/**
 * Available analyzers for different conditions
 */
BeamAnalysis.analyzer = {};
/**
 * Calculate deflection, bending stress and shear stress for a simply supported beam
 *
 * @param {Beam}   beam   The beam object
 * @param {Number}  load    The applied load
 */
BeamAnalysis.analyzer.simplySupported = class {
    constructor(beam, load) {
        this.beam = beam;
        this.load = load;
    }
    getDeflectionEquation(beam, load) {
        const w = load;
        const L = beam.primarySpan;
        const EI =  beam.material.properties.EI;
        const j2 = beam.j2;

        const EIConverter = new UnitConverter('EI', EI, 'nmm2');
        const EIinNmm = EIConverter.convertEItoKnm2();

        return function (x) {
            const deflectionEquation = -(((w*x)/(24*EIinNmm))*(Math.pow(L, 3)-2*L*Math.pow(x, 2)+Math.pow(x, 3))*j2*1000);  
            return {
                x: x,
                y: deflectionEquation
            };
        };
    }
    getBendingMomentEquation(beam, load) {
        const w = load;
        const L = beam.primarySpan;

        return function (x) {
            const bendingMomentEquation = ((w*x/2)*(L-x))*-1;
            return {
                x: x,
                y: bendingMomentEquation
            };
        };
    }
    getShearForceEquation(beam, load) {
        const w = load;
        const L = beam.primarySpan;

        return function (x) {
            const shearForceEquation = w*((L/2)-x);

            return {
                x: x,
                y: shearForceEquation
            };
        };
    }
};


/**
 * Calculate deflection, bending stress and shear stress for a beam with two spans of equal condition
 *
 * @param {Beam}   beam   The beam object
 * @param {Number}  load    The applied load
 */
BeamAnalysis.analyzer.twoSpanUnequal = class {
    constructor(beam, load) {
        this.beam = beam;
        this.load = load;
    }
    getMomentsValue(beam, load){
        const w = load;
        const l1 = beam.primarySpan;
        const l2 = beam.secondarySpan;

        const m1 = -((Math.pow((w*l2), 3)+Math.pow((w.l1), 3)) / (8*(l1+l2)));
        return m1;
    }
    getR1Value(beam, load){
        const m1 = this.getMomentsValue(beam, load);
        const w = load;
        const l1 = beam.primarySpan;

        const r1 = (m1/l1) + ((w*l1)/2);
        return r1;
    }
    getR2Value(beam, load){
        const w = load;
        const l1 = beam.primarySpan;
        const l2 = beam.secondarySpan;
        const r1 = this.getR1Value(beam, load);
        const r3 = this.getR3Value(beam, load);

        const r2 = (w*l1) + (w*l2) - r1 - r3;
        return r2;
    }
    getR3Value(beam, load){
        const m1 = this.getMomentsValue(beam, load);
        const w = load;
        const l2 = beam.secondarySpan;

        const r3 = (m1/l2) + ((w*l2)/2);
        return r3;
    }
    getDeflectionEquation(beam, load) {
        return function (x) {
            return {
                x: x,
                y: null
            };
        };
    }
    getBendingMomentEquation(beam, load) {
        return function (x) {
            return {
                x: x,
                y: null
            };
        };
    }
    // Todo : find "a" in excel
    getShearForceEquation(beam, load) {
        return function (x) {
            if(x==0){
                const r1 = this.getR1Value(beam, load);
                const shearForceEquation = r1;
            }
            else if(){

            }

            return {
                x: x,
                y: shearForceEquation
            };
        };
    }
};
