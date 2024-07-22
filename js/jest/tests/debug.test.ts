import SpyInstance = jest.SpyInstance;

let consoleSpy: SpyInstance<void, [ message?: any, ...optionalParams: any[] ], any>;
let serializeObject: Function;
let addObjectAsUrlParams: Function;

describe( 'debug.ts', () => {
	/**
	 * Mock the console.log method before the module is loaded to catch
	 * the output.
	 */
	beforeAll( async () => {
		consoleSpy = jest.spyOn( console, 'log' ).mockImplementation();

		const module = await import( '../../../../advanced-sidebar-menu/js/src/debug' );
		serializeObject = module.serializeObject;
		addObjectAsUrlParams = module.addObjectAsUrlParams;
	} );


	it( 'Should serialize object', () => {
		const params = {
			include_parent: false,
			include_childless_parent: false,
			link_expand_levels: {
				all: 'checked',
			},
		};
		const serialized = serializeObject( params );
		expect( serialized ).toStrictEqual( [
			[ 'asm_debug[include_parent]', 'false' ],
			[ 'asm_debug[include_childless_parent]', 'false' ],
			[ 'asm_debug[link_expand_levels][all]', 'checked' ],
		] );
	} );


	it( 'Should add an object as URL params', () => {
		const url = 'https://example.com';
		const params = {
			include_parent: false,
			include_childless_parent: false,
			link_expand_levels: {
				all: 'checked',
			},
		};
		const urlWithParams = addObjectAsUrlParams( url, params );
		expect( urlWithParams ).toBe( 'https://example.com/?asm_debug%5Binclude_parent%5D=false&asm_debug%5Binclude_childless_parent%5D=false&asm_debug%5Blink_expand_levels%5D%5Ball%5D=checked' );
	} );

	it( 'Should output information to the console', () => {
		expect( consoleSpy ).toHaveBeenCalledWith( 'Advanced Sidebar Info:' );
		expect( consoleSpy ).toHaveBeenCalledWith( {
			...window.asm_debug,
			menus: 'See below for menus.',
		} );
		expect( consoleSpy ).toHaveBeenCalledWith( 'Advanced Sidebar Menus:' );
		expect( consoleSpy ).toHaveBeenCalledWith( window.asm_debug.menus );
		expect( consoleSpy ).toHaveBeenCalledWith( 'The `advancedSidebarMenuDebug` function is available for debugging.' );
		expect( consoleSpy ).toHaveBeenCalledTimes( 5 );
	} );
} );
