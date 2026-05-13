"use strict";

/** ============================ Converter Helper ============================ */

/**
 * Converter Helper to convert unit.
 *
 * @param {String} name     Material name
 * @param {Number} amount   Material amount
 * @param {Number} unit     Material unit
 */
class UnitConverter {
  constructor(name, amount, unit) {
    this.name = name;
    this.amount = amount;
    this.unit = unit;
  }

  convertEItoKnm2() {
    if (!this.name == "EI") {
      throw new Error("Name not matches");
    }
    if (!this.unit == "Knm2") {
      throw new Error("unit already in Knm2");
    }

    return this.amount / Math.pow(1000, 3);
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
      condition: "simply-supported",
    };

    this.analyzer = {
      "simply-supported": new BeamAnalysis.analyzer.simplySupported(),
      "two-span-unequal": new BeamAnalysis.analyzer.twoSpanUnequal(),
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
        equation: analyzer.getDeflectionEquation(beam, load),
        condition: condition,
        formula: "deflection",
        criticalPoints: analyzer.getCriticalPoints(beam, load),
      };
    } else {
      throw new Error("Invalid condition");
    }
  }
  getBendingMoment(beam, load, condition) {
    var analyzer = this.analyzer[condition];

    if (analyzer) {
      return {
        beam: beam,
        load: load,
        equation: analyzer.getBendingMomentEquation(beam, load),
        condition: condition,
        formula: "bending-moment",
        criticalPoints: analyzer.getCriticalPoints(beam, load),
      };
    } else {
      throw new Error("Invalid condition");
    }
  }
  getShearForce(beam, load, condition) {
    var analyzer = this.analyzer[condition];

    if (analyzer) {
      return {
        beam: beam,
        load: load,
        equation: analyzer.getShearForceEquation(beam, load),
        condition: condition,
        formula: "shear-force",
        criticalPoints: null, //shear-force doen't have critical points
      };
    } else {
      throw new Error("Invalid condition");
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
    const EI = beam.material.properties.EI;
    const j2 = beam.j2;

    const EIConverter = new UnitConverter("EI", EI, "nmm2");
    const EIinNmm = EIConverter.convertEItoKnm2();

    return function (x) {
      const deflectionEquation = -(((w * x) / (24 * EIinNmm)) * (Math.pow(L, 3) - 2 * L * Math.pow(x, 2) + Math.pow(x, 3)) * j2 * 1000);
      return {
        x: x,
        y: deflectionEquation,
      };
    };
  }
  getBendingMomentEquation(beam, load) {
    const w = load;
    const L = beam.primarySpan;

    return function (x) {
      const bendingMomentEquation = ((w * x) / 2) * (L - x) * -1;
      return {
        x: x,
        y: bendingMomentEquation,
      };
    };
  }
  getShearForceEquation(beam, load) {
    const w = load;
    const L = beam.primarySpan;

    return function (x) {
      const shearForceEquation = w * (L / 2 - x);

      return {
        x: x,
        y: shearForceEquation,
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
  getMomentsValue(beam, load) {
    const w = load;
    const l1 = beam.primarySpan;
    const l2 = beam.secondarySpan;

    const m1 = -((w * Math.pow(l2, 3) + w * Math.pow(l1, 3)) / (8 * (l1 + l2)));

    return m1;
  }
  getR1Value(beam, load) {
    const m1 = this.getMomentsValue(beam, load);
    const w = load;
    const l1 = beam.primarySpan;

    const r1 = m1 / l1 + (w * l1) / 2;
    return r1;
  }
  getR2Value(beam, load) {
    const w = load;
    const l1 = beam.primarySpan;
    const l2 = beam.secondarySpan;
    const r1 = this.getR1Value(beam, load);
    const r3 = this.getR3Value(beam, load);

    const r2 = w * l1 + w * l2 - r1 - r3;
    return r2;
  }
  getR3Value(beam, load) {
    const m1 = this.getMomentsValue(beam, load);
    const w = load;
    const l2 = beam.secondarySpan;

    const r3 = m1 / l2 + (w * l2) / 2;
    return r3;
  }
  getCriticalPoints(beam, load) {
    const w = load;
    const r1 = this.getR1Value(beam, load);
    const r2 = this.getR2Value(beam, load);

    const criticalpoints = [r1 / w, (r1 + r2) / w];
    return criticalpoints;
  }
  getDeflectionEquation(beam, load) {
    return (x) => {
      const w = load;
      const l1 = beam.primarySpan;
      const r1 = this.getR1Value(beam, load);
      const r2 = this.getR2Value(beam, load);
      const EI = beam.material.properties.EI;
      const j2 = beam.j2;

      const EIConverter = new UnitConverter("EI", EI, "nmm2");
      const EIinNmm = EIConverter.convertEItoKnm2();

      let deflectionEquation;

      if (x >= 0 && x <= l1) {
        deflectionEquation = (x / (24 * EIinNmm)) * (4 * r1 * Math.pow(x, 2) - w * Math.pow(x, 3) + w * Math.pow(l1, 3) - 4 * r1 * Math.pow(l1, 2)) * 1000 * j2;
      } else {
        deflectionEquation =
          (((r1 * x) / 6) * (Math.pow(x, 2) - Math.pow(l1, 2)) + ((r2 * x) / 6) * (Math.pow(x, 2) - 3 * l1 * x + 3 * Math.pow(l1, 2)) - (r2 * Math.pow(l1, 3)) / 6 - ((w * x) / 24) * (Math.pow(x, 3) - Math.pow(l1, 3))) *
          (1 / EIinNmm) *
          1000 *
          j2;
      }
      return {
        x: x,
        y: deflectionEquation,
      };
    };
  }
  getBendingMomentEquation(beam, load) {
    return (x) => {
      const r1 = this.getR1Value(beam, load);
      const r2 = this.getR2Value(beam, load);
      const w = load;
      const l1 = beam.primarySpan;
      const l2 = beam.secondarySpan;
      const L = l1+l2;

      let bendingMomentEquation;

      if (x == 0 || x == L) {
        bendingMomentEquation = 0 * x;
      } else if (x > 0 && x < l1) {
        bendingMomentEquation = -(r1 * x - 0.5 * w * Math.pow(x, 2));
      } else if (x == l1) {
        bendingMomentEquation = -(r1 * l1 - 0.5 * w * Math.pow(l1, 2));
      } else {
        bendingMomentEquation = -(x * r1 + r2 * (x - l1) - 0.5 * w * Math.pow(x, 2));
      }

      return {
        x: x,
        y: bendingMomentEquation,
      };
    };
  }
  getShearForceEquation(beam, load) {
    return (x, side = "left") => {
      const w = load;
      const l1 = beam.primarySpan;
      const l2 = beam.secondarySpan;
      const l = l1 + l2;

      const r1 = this.getR1Value(beam, load);
      const r2 = this.getR2Value(beam, load);

      let shearForceEquation;

      if (x == 0) {
        shearForceEquation = r1;
      } else if (x > 0 && x < l1) {
        shearForceEquation = r1 - w * x;
      } else if (x == l1) {
        if (side == "right") {
          shearForceEquation = r1 + r2 - w * l1;
        } else {
          shearForceEquation = r1 - w * l1;
        }
      } else if (l1 < x && x <= l) {
        shearForceEquation = r1 + r2 - w * x;
      }

      return {
        x: x,
        y: shearForceEquation,
      };
    };
  }
};
