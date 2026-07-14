(function (wp) {
	const { __ } = wp.i18n;
	const { createElement: el, Fragment } = wp.element;
	const { useSelect, useDispatch } = wp.data;
	const { PluginDocumentSettingPanel } = wp.editPost;
	const { ColorPalette, MediaUpload, MediaUploadCheck } = wp.blockEditor;
	const { Button, SelectControl } = wp.components;
	const { registerPlugin } = wp.plugins;

	const META_KEY = "_gb_transparent_header_text_color";
	const GITE_HEADER_STYLE_KEY = "_gb_gite_photo_header_style";
	const GITE_HEADER_FRAME_KEY = "_gb_gite_photo_header_frame";
	const GITE_HEADER_BACKGROUND_KEY = "_gb_gite_photo_header_background_id";

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

	const GitePhotoHeaderSettings = () => {
		const settings = useSelect((select) => {
			const editor = select("core/editor");
			const meta = editor.getEditedPostAttribute("meta") || {};
			const backgroundId = Number(meta[GITE_HEADER_BACKGROUND_KEY] || 0);

			return {
				background: backgroundId ? select("core").getMedia(backgroundId) : null,
				backgroundId,
				frame: meta[GITE_HEADER_FRAME_KEY] || "ornate-wood",
				style: meta[GITE_HEADER_STYLE_KEY] || "classic",
				template: editor.getEditedPostAttribute("template") || "",
			};
		}, []);
		const { editPost } = useDispatch("core/editor");

		if (settings.template !== "page-gite") {
			return null;
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: "gb-gite-photo-header-settings",
				title: __("Header photo du gîte", "theme-gite-broceliande-wp"),
			},
			el(SelectControl, {
				label: __("Modèle de galerie", "theme-gite-broceliande-wp"),
				value: settings.style,
				options: [
					{ label: __("Galerie classique", "theme-gite-broceliande-wp"), value: "classic" },
					{ label: __("Cadre bois et Polaroids", "theme-gite-broceliande-wp"), value: "frames" },
				],
				onChange: (style) => editPost({ meta: { [GITE_HEADER_STYLE_KEY]: style } }),
			}),
			settings.style === "frames"
				? el(
					Fragment,
					null,
					el(SelectControl, {
						label: __("Cadre de l’image principale", "theme-gite-broceliande-wp"),
						value: settings.frame,
						options: [
							{ label: __("Cadre bois rustique sombre", "theme-gite-broceliande-wp"), value: "rustic-dark" },
							{ label: __("Cadre doré ancien", "theme-gite-broceliande-wp"), value: "antique-gold" },
							{ label: __("Cadre bois orné", "theme-gite-broceliande-wp"), value: "ornate-wood" },
						],
						help: __("Cadre utilisé autour de la grande photo du header.", "theme-gite-broceliande-wp"),
						onChange: (frame) => editPost({ meta: { [GITE_HEADER_FRAME_KEY]: frame } }),
					}),
					settings.background && settings.background.source_url
						? el("img", {
							alt: "",
							style: { display: "block", height: "110px", marginBottom: "12px", objectFit: "cover", width: "100%" },
							src: settings.background.source_url,
						})
						: null,
					el(
						MediaUploadCheck,
						null,
						el(MediaUpload, {
							allowedTypes: ["image"],
							value: settings.backgroundId,
							onSelect: (media) => editPost({ meta: { [GITE_HEADER_BACKGROUND_KEY]: media.id } }),
							render: ({ open }) => el(Button, { variant: "secondary", onClick: open }, settings.backgroundId
								? __("Remplacer l’image de fond", "theme-gite-broceliande-wp")
								: __("Choisir l’image de fond", "theme-gite-broceliande-wp")),
						})
					),
					settings.backgroundId
						? el(Button, {
							isDestructive: true,
							isLink: true,
							onClick: () => editPost({ meta: { [GITE_HEADER_BACKGROUND_KEY]: 0 } }),
						}, __("Retirer l’image", "theme-gite-broceliande-wp"))
						: null
				)
				: null
		);
	};

	registerPlugin("gb-transparent-header-settings", {
		render: TransparentHeaderSettings,
	});

	registerPlugin("gb-gite-photo-header-settings", {
		render: GitePhotoHeaderSettings,
	});
})(window.wp);
