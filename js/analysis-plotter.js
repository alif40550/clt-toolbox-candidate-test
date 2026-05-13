"use strict";
/**
 * Parent class for formula counter
 *
 * @param {Object} beam         beam object 
 * @param {Number} load         load
 * @param {Function} equation   Equation function
 * @param {String} formula      Formula type ex:"shear-force", etc.
 */
class FormulaCounter {
  constructor(beam, load, equation, formula) {
    this.beam = beam;
    this.load = load;
    this.equation = equation;
    this.formula = formula;
    this.results = [];
  }
  getResults() {
    return this.results;
  }
}
/**
 * class for counting simply supported condition
 *
 * @param {Object} beam         beam object 
 * @param {Number} load         load
 * @param {Function} equation   Equation function
 * @param {String} formula      Formula type ex:"shear-force", etc.
 */
class SimplySupportedCounter extends FormulaCounter {
  constructor(beam, load, equation, formula) {
    super(beam, load, equation, formula);
  }
  count() {
    const l1 = this.beam.primarySpan;
    const step = l1 / 10.0;

    for (let i = 0; i <= l1; i += step) {
      const x = i;
      this.results.push(this.equation(x));
    }
    return super.getResults();
  }
}
/**
 * class for counting Two Span Unequal condition
 *
 * @param {Object} beam           beam object 
 * @param {Number} load           load
 * @param {Function} equation     Equation function
 * @param {String} formula        Formula type ex:"shear-force", etc.
 * @param {Array} criticalPoints  critical points for bending moments and deflection equation
 */
class TwoSpanUnequalCounter extends FormulaCounter {
  constructor(beam, load, equation, formula, criticalPoints) {
    super(beam, load, equation, formula);
    this.criticalPoints = criticalPoints;
  }
  addCriticalPoints() {
    this.criticalPoints.forEach((point) => {
      this.results.push(this.equation(point));
    });
    this.results = Array.from(this.results).sort((a, b) => a.x - b.x);

    return super.getResults();
  }
  shearForce() {
    const l1 = this.beam.primarySpan;
    const l2 = this.beam.secondarySpan;
    const L = l1 + l2;
    const step = L / 10.0;
    for (let x = 0; x <= l1; x += step) {
      this.results.push(equation(x));
    }
    const peakLeft = this.results.push(equation(l1));
    for (let x = l1; x <= L; x += step) {
      if (x == l1) {
        this.results.push(equation(x, "right"));
      } else {
        this.results.push(equation(x));
      }
    }
    const maxL = this.results.push(equation(L));

    return super.getResults();
  }
  bendingMoment() {
    const l1 = this.beam.primarySpan;
    const l2 = this.beam.secondarySpan;
    const L = l1 + l2;
    const step = L / 10.0;
    for (let x = 0; x <= l1; x += step) {
      this.results.push(equation(x));
    }
    for (let x = l1; x <= L; x += step) {
      this.results.push(equation(x));
    }
    this.results = this.addCriticalPoints();
    const maxL = this.results.push(equation(L));

    return super.getResults();
  }
  deflection() {
    const l1 = this.beam.primarySpan;
    const l2 = this.beam.secondarySpan;
    const L = l1 + l2;
    let step = l1 / 10.0;
    for (let x = 0; x <= l1; x += step) {
      this.results.push(equation(x));
    }
    step = l2 / 10.0;
    for (let x = l1 + step; x <= L; x += step) {
      this.results.push(equation(x));
    }
    this.results = this.addCriticalPoints();

    return super.getResults();
  }
  count() {
    if (this.formula == "shear-force") {
      this.results = this.shearForce();
    } else if (this.formula == "bending-moment") {
      this.results = this.bendingMoment();
    } else if(this.formula == "deflection"){
      this.results = this.deflection();
    } else{
      throw new Error("formula not valid");
    }

    return super.getResults();
  }
}

/**
 * Plot result from the beam analysis calculation into a graph
 */
class AnalysisPlotter {
  constructor(container) {
    this.container = container;
  }

  /**
   * Plot equation.
   *
   * @param {Object{beam : Beam, load : float, equation: Function, condition : string, formula: string}}  The equation data
   */
  plot(data) {
    let formulaCounter;

    if (data.condition == "simply-supported") {
      formulaCounter = new SimplySupportedCounter(data.beam, data.load, data.equation, data.formula);
    } else if (data.condition == "two-span-unequal") {
      formulaCounter = new TwoSpanUnequalCounter(data.beam, data.load, data.equation, data.formula, data.criticalPoints);
    }

    const results = formulaCounter.count();
    console.log(results);

    new Chart(document.getElementById(this.container), {
      type: "line",
      data: {
        labels: results.map((point) => point.x),
        datasets: [
          {
            label: "Dataset",
            data: results.map((point) => point.y),
            borderColor: "#378ADD",
            backgroundColor: "rgba(55, 138, 221, 0.1)",
            borderWidth: 2,
            pointRadius: 5,
            tension: 0.3,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { title: { display: true, text: "X" } },
          y: { title: { display: true, text: "Y" } },
        },
      },
    });
  }
}
