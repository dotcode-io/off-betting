import { CountUp } from "countup.js";
import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";


Alpine.directive(
    "countup",
    (el, { expression, modifiers }, { evaluateLater, effect }) => {
        const evaluate = evaluateLater(expression);
        let countUpInstance = null;

        // Default options for CountUp
        const options = {
            duration: 2, // Fast animation if "fast" is a modifier
            separator: ",",
            decimalPlaces: 2,
        };

        // Initialize CountUp.js and observe changes
        const updateCounter = (newValue) => {
            if (countUpInstance) {
                countUpInstance.update(newValue);
            } else {
                countUpInstance = new CountUp(el, newValue, options);
                if (!countUpInstance.error) {
                    countUpInstance.start();
                } else {
                    console.error(countUpInstance.error);
                }
            }
        };

        // Reactively watch for changes in the value
        effect(() => {
            evaluate((newValue) => {
                updateCounter(newValue);
            });
        });
    }
);

Livewire.start();
import "./bootstrap";
