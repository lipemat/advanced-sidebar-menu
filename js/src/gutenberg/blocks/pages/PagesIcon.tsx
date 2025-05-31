type Props = Record<string, never>;

const PagesIcon = ( {}: Props ) => {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
			<rect x="0" fill="none" width="20" height="20" />
			<g>
				<path
					fill="#044567"
					d="M19 17V2c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v15c0 .55.45 1 1 1h15c.55 0 1-.45 1-1z M4 3h13v4H4V3z M5 4h3v2H5V4zm4 0h3v2H9V4zm4 0h3v2H13V4z M4.5 9 c .28 0 .5 .22 .5 .5s-.22.5-.5.5s-.5-.22-.5-.5s.22-.5 .5-.5z M6 9h4v1H6v-1z M4.5 12 c .28 0 .5 .22 .5 .5s-.22.5-.5.5s-.5-.22-.5-.5s.22-.5 .5-.5z M6 12h4v1H6v-1z M4.5 15 c .28 0 .5 .22 .5 .5s-.22.5-.5.5s-.5-.22-.5-.5s.22-.5 .5-.5z M6 15h4v1H6v-1z M12 9 H15.5 L17 10.5 V15.5 H12 V9 Z M15.5 9 V10.5 H17 M12.5 12 H16.5 M12.5 13.5 H16.5"
				/>
			</g>
		</svg>
	);
};

export default PagesIcon;
