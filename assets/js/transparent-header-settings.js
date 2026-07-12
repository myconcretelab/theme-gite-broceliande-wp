(function (wp) {
	const { __ } = wp.i18n;
	const { createElement: el } = wp.element;
	const { useSelect, useDispatch } = wp.data;
	const { PluginDocumentSettingPanel } = wp.editPost;
	const { ColorPalette } = wp.blockEditor;
	const { registerPlugin } = wp.plugins;

	const META_KEY = "_gb_transparent_header_text_color";

	const TransparentHeaderSettings = () => {
		const { color, template } = useSelect((select) => {
			const editor = select("core/editor");
			const meta = editor.getEditedPostAttribute("meta") || {};

			return {
				color: meta[META_KEY] || "",
				template: editor.getEditedPostAttribute("template") || "",
			};
		}, []);
		const { editPost } = useDispatch("core/editor");

		if (template !== "page-transparent-header") {
			return null;
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: "gb-transparent-header-settings",
				title: __("Header transparent", "theme-gite-broceliande-wp"),
				className: "gb-transparent-header-settings",
			},
			el("p", null, __("Couleur du titre et des liens de navigation avant le défilement.", "theme-gite-broceliande-wp")),
			el(ColorPalette, {
				value: color,
				clearable: true,
				disableCustomColors: false,
				onChange: (nextColor) => editPost({
					meta: { [META_KEY]: nextColor || "" },
				}),
			})
		);
	};

	registerPlugin("gb-transparent-header-settings", {
		render: TransparentHeaderSettings,
	});
})(window.wp);
