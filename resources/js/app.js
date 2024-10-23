// Import jQuery
import $ from 'jquery';

// Import Popper.js
import { createPopper } from '@popperjs/core';

// Import Bootstrap
import 'bootstrap';

// Initialize Bootstrap dropdowns
$(function() {
    $('.dropdown-toggle').dropdown();
});
