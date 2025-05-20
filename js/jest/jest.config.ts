import type {Config} from 'jest';

const config: Config = require( '@lipemat/js-boilerplate/config/jest.config' );

/**
 * @todo Remove this when required js-boilerplate is version 10.12.1+.
 */
config.prettierPath = null;

module.exports = config;
