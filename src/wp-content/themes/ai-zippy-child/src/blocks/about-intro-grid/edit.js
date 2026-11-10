import { useBlockProps, RichText, MediaUpload, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, Button, SelectControl, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { introText, col1Text, col2Text, images, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `abig has-text-align-${textAlign || "left"}` });

	const updateImage = (index, key, value) => {
		const newImages = [...images];
		newImages[index] = { ...newImages[index], [key]: value };
		setAttributes({ images: newImages });
	};

	// Group images into 3 columns for editing view
	const columns = [
		[{ data: images[0], idx: 0 }, { data: images[1], idx: 1 }],
		[{ data: images[2], idx: 2 }, { data: images[3], idx: 3 }],
		[{ data: images[4], idx: 4 }, { data: images[5], idx: 5 }],
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title="Căn chữ" initialOpen={false}>
					<SelectControl
						label="Căn chữ"
						value={textAlign || "left"}
						options={[
							{ label: "Trái", value: "left" },
							{ label: "Giữa", value: "center" },
							{ label: "Phải", value: "right" },
							{ label: "Đều hai bên", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
				<PanelBody title="Collage Images" initialOpen={true}>
					<div style={{ display: "flex", flexDirection: "column", gap: 15 }}>
						{images.map((image, index) => (
							<div key={index} style={{ border: "1px solid #ddd", padding: 12, borderRadius: 6, backgroundColor: "#f9f9f9" }}>
								<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 10 }}>
									<strong>Box #{index + 1}</strong>
									{image.imageUrl && (
										<Button
											variant="link"
											isDestructive
											onClick={() => {
												updateImage(index, "imageId", 0);
												updateImage(index, "imageUrl", "");
												updateImage(index, "title", "");
												updateImage(index, "subtitle", "");
											}}
											style={{ fontSize: 11, padding: 0, height: "auto" }}
										>
											Clear
										</Button>
									)}
								</div>
								<div style={{ display: "flex", gap: 10, alignItems: "center", marginBottom: 10 }}>
									<div style={{ width: 60, height: 60, backgroundColor: "#eee", borderRadius: 4, display: "flex", alignItems: "center", justifyContent: "center", overflow: "hidden", flexShrink: 0 }}>
										{image.imageUrl ? (
											<img src={image.imageUrl} style={{ width: "100%", height: "100%", objectFit: "cover" }} alt="" />
										) : (
											<span style={{ fontSize: 9, color: "#888" }}>Empty</span>
										)}
									</div>
									<MediaUpload
										onSelect={(media) => {
											updateImage(index, "imageId", media.id);
											updateImage(index, "imageUrl", media.url);
										}}
										allowedTypes={["image"]}
										value={image.imageId}
										render={({ open }) => (
											<Button variant="secondary" onClick={open} style={{ fontSize: 11 }}>
												{image.imageUrl ? "Đổi ảnh" : "Chọn ảnh"}
											</Button>
										)}
									/>
								</div>
								{image.imageUrl && (
									<div style={{ display: "flex", flexDirection: "column", gap: 8 }}>
										<TextControl
											label="Title"
											value={image.title || ""}
											onChange={(val) => updateImage(index, "title", val)}
											style={{ marginBottom: 0 }}
										/>
										<TextControl
											label="Subtitle"
											value={image.subtitle || ""}
											onChange={(val) => updateImage(index, "subtitle", val)}
											style={{ marginBottom: 0 }}
										/>
									</div>
								)}
							</div>
						))}
					</div>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="abig__container">
					<div className="abig__content">
						<RichText
							tagName="h2"
							className="abig__intro"
							value={introText}
							onChange={(val) => setAttributes({ introText: val })}
							placeholder="Introduce statement..."
						/>
						
						<div className="abig__grid-text">
							<RichText
								tagName="p"
								className="abig__para"
								value={col1Text}
								onChange={(val) => setAttributes({ col1Text: val })}
								placeholder="First column detail..."
							/>
							<RichText
								tagName="p"
								className="abig__para"
								value={col2Text}
								onChange={(val) => setAttributes({ col2Text: val })}
								placeholder="Second column detail..."
							/>
						</div>
					</div>

					<div className="abig__collage">
						{columns.map((col, colIdx) => (
							<div key={colIdx} className={`abig__collage-column abig__collage-column--${colIdx + 1}`}>
								{col.map(({ data, idx }) => (
									<div key={idx} className="abig__collage-box">
										{data.imageUrl ? (
											<>
												<img className="abig__collage-img" src={data.imageUrl} alt="" />
												<div className="abig__info-overlay">
													<div className="abig__info-content">
														<RichText
															tagName="h3"
															className="abig__info-title"
															value={data.title || ""}
															onChange={(val) => updateImage(idx, "title", val)}
															placeholder="Enter Title..."
															onClick={(e) => e.stopPropagation()}
														/>
														<RichText
															tagName="p"
															className="abig__info-subtitle"
															value={data.subtitle || ""}
															onChange={(val) => updateImage(idx, "subtitle", val)}
															placeholder="Enter Subtitle..."
															onClick={(e) => e.stopPropagation()}
														/>
													</div>
												</div>
											</>
										) : (
											<MediaUpload
												onSelect={(media) => {
													updateImage(idx, "imageId", media.id);
													updateImage(idx, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												render={({ open }) => (
													<div className="abig__collage-box--placeholder" onClick={open} style={{ width: "100%", height: "100%", display: "flex", alignItems: "center", justifyContent: "center" }}>
														<span className="abig__placeholder-text">+ Box #{idx + 1}</span>
													</div>
												)}
											/>
										)}
									</div>
								))}
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}
