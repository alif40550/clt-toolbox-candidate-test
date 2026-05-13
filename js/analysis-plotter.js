"use strict";
// Todo : split the analysis ploter to formulacounter class after finish

/**
 * Plot result from the beam analysis calculation into a graph
 */
class AnalysisPlotter {
  constructor(container, formulaCounter) {
    this.container = container;
    this.formulaCounter = formulaCounter;
  }

  /**
   * Plot equation.
   *
   * @param {Object{beam : Beam, load : float, equation: Function, condition : string, formula: string}}  The equation data
   */

  countSimplySupported(l1, equation) {
    let results = [];
    const step = l1 / 10.0;

    for (let i = 0; i <= l1; i += step) {
      const x = i.toFixed(2);
      results.push(equation(x));
    }
    return results;
  }
  countTwoSpanUnequal(L, l1, equation, formula, criticalPoints) {
    let results = [];
    const step = L / 10.0;
    const l2 = L - l1;

    if (formula == "shear-force") {
      for (let i = 0; i <= l1; i += step) {
        const x = i.toFixed(2);
        results.push(equation(x));
      }
      const peakLeft = results.push(equation(l1.toFixed(2)));
      for (let i = l1; i <= L; i += step) {
        const x = i.toFixed(2);
        if (i == l1) {
          results.push(equation(x, "right"));
        } else {
          results.push(equation(x));
        }
      }
      const maxL = results.push(equation(L.toFixed(2)));
    } else if(formula=="bending-moment"){
      for (let i = 0; i <= l1; i += step) {
        const x = i.toFixed(2);
        results.push(equation(x));
      }
      for (let i = l1; i <= L; i += step) {
        const x = i.toFixed(2);
        results.push(equation(x));
      }
      criticalPoints.forEach((point) => {
        results.push(equation(point.toFixed(2)));
      });
      const maxL = results.push(equation(L.toFixed(2)));
      results = Array.from(results).sort((a, b) => a.x - b.x);
    }
    else{
      let step = l1/10.0;
      for (let i = 0; i <= l1; i += step) {
        const x = i.toFixed(2);
        results.push(equation(x));
      }
      step = l2/10.0;
      for (let i = l1+step; i <= L; i += step) {
        const x = i.toFixed(2);
        results.push(equation(x));
      }
      criticalPoints.forEach((point) => {
        results.push(equation(point.toFixed(2)));
      });
      results = Array.from(results).sort((a, b) => a.x - b.x);
    }

    return results;
  }
  plot(data) {
    let results = [];
    const l1 = data.beam.primarySpan;
    const l2 = data.beam.secondarySpan;
    const L = l1 + l2;

    if (data.condition == "simply-supported") {
      results = this.countSimplySupported(l1, data.equation);
    } else if (data.condition == "two-span-unequal") {
      results = this.countTwoSpanUnequal(L, l1, data.equation, data.formula, data.criticalPoints);
    }
    console.log(results);
  }
}
