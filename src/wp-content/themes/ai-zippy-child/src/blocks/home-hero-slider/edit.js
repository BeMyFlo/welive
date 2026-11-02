import { useState } from "@wordpress/element";
import { useBlockProps, RichText, MediaUpload, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, slides, textAlign, ctaText, ctaUrl } = attributes;
	const [activeSlideIndex, setActiveSlideIndex] = useState(0);
	const blockProps = useBlockProps({ className: `hhs has-text-align-${textAlign || "left"}` });

	const updateSlide = (index, key, value) => {
		const newSlides = [...slides];
		newSlides[index] = { ...newSlides[index], [key]: value };
		setAttributes({ slides: newSlides });
	};

	const addSlide = () => {
		const newSlides = [
			...slides,
			{ 
				imageId: 0, 
				imageUrl: "", 
				artistName: "New Artist", 
				link: "",
				title: "New Slide Title",
				subtitle: "New slide description goes here."
			}
		];
		setAttributes({ slides: newSlides });
		setActiveSlideIndex(slides.length);
	};

	const removeSlide = (index) => {
		const newSlides = slides.filter((_, i) => i !== index);
		setAttributes({ slides: newSlides });
		if (activeSlideIndex >= newSlides.length) {
			setActiveSlideIndex(Math.max(0, newSlides.length - 1));
		}
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Text Alignment" initialOpen={false}>
					<SelectControl
						label="Text Alignment"
						value={textAlign || "left"}
						options={[
							{ label: "Left", value: "left" },
							{ label: "Center", value: "center" },
							{ label: "Right", value: "right" },
							{ label: "Justify", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
				<PanelBody title="CTA Button" initialOpen={false}>
					<TextControl label="Link" value={ctaUrl} onChange={(val) => setAttributes({ ctaUrl: val })} />
				</PanelBody>
				<PanelBody title="Manage Slides Info" initialOpen={true}>
					{slides.map((slide, index) => (
						<div 
							key={index} 
							style={{ 
								borderBottom: "1px solid #ccc", 
								paddingBottom: 15, 
								marginBottom: 15,
								backgroundColor: activeSlideIndex === index ? "rgba(236, 70, 0, 0.05)" : "transparent",
								padding: "8px",
								borderRadius: "4px"
							}}
							onClick={() => setActiveSlideIndex(index)}
						>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>Slide #{index + 1} {activeSlideIndex === index ? "(Active)" : ""}</strong>
								<Button isDestructive variant="link" onClick={(e) => {
									e.stopPropagation();
									removeSlide(index);
								}}>
									Remove
								</Button>
							</div>
							<TextControl
								label="Artist Name"
								value={slide.artistName}
								onChange={(val) => updateSlide(index, "artistName", val)}
							/>
							<TextControl
								label="Link"
								value={slide.link}
								onChange={(val) => updateSlide(index, "link", val)}
							/>
							<TextControl
								label="Slide Title"
								value={slide.title || ""}
								onChange={(val) => updateSlide(index, "title", val)}
							/>
							<TextareaControl
								label="Slide Subtitle"
								value={slide.subtitle || ""}
								onChange={(val) => updateSlide(index, "subtitle", val)}
							/>
						</div>
					))}
					<Button variant="primary" onClick={addSlide} style={{ width: "100%", marginTop: 10 }}>
						Add New Slide
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="hhs__container">
					{/* Overlay Info */}
					<div className="hhs__content">
						<div style={{ marginBottom: 10, fontSize: 12, opacity: 0.6 }}>
							Editing content for <strong>Slide #{activeSlideIndex + 1}: {slides[activeSlideIndex]?.artistName || "Unnamed"}</strong> (Click slide below to change)
						</div>
						<RichText
							tagName="h1"
							className="hhs__title"
							value={slides[activeSlideIndex]?.title !== undefined ? slides[activeSlideIndex].title : title}
							onChange={(val) => updateSlide(activeSlideIndex, "title", val)}
							placeholder="Slide Title..."
						/>
						<RichText
							tagName="p"
							className="hhs__subtitle"
							value={slides[activeSlideIndex]?.subtitle !== undefined ? slides[activeSlideIndex].subtitle : subtitle}
							onChange={(val) => updateSlide(activeSlideIndex, "subtitle", val)}
							placeholder="Slide Description..."
						/>
						<div className="hhs__cta">
							<RichText
								tagName="span"
								className="hhs__cta-label"
								value={ctaText}
								onChange={(val) => setAttributes({ ctaText: val })}
								placeholder="Button text..."
								allowedFormats={[]}
							/>
						</div>
						<Button variant="primary" onClick={addSlide} style={{ marginTop: 15 }}>
							Add Slide
						</Button>
					</div>

					{/* Slides list editor view */}
					<div className="hhs__grid-preview">
						{slides.map((slide, index) => (
							<div 
								key={index} 
								className={`hhs__slide-card ${activeSlideIndex === index ? "is-active" : ""}`}
								onClick={() => setActiveSlideIndex(index)}
								style={{ cursor: "pointer" }}
							>
								<div className="hhs__slide-img-wrap">
									{slide.imageUrl ? (
										<img className="hhs__slide-img" src={slide.imageUrl} alt="" />
									) : (
										<div className="hhs__slide-placeholder">
											<MediaUpload
												onSelect={(media) => {
													updateSlide(index, "imageId", media.id);
													updateSlide(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												render={({ open }) => (
													<Button variant="secondary" onClick={open} onClick={(e) => {
														e.stopPropagation();
														open();
													}}>
														Select Image
													</Button>
												)}
											/>
										</div>
									)}
									{slide.imageUrl && (
										<div className="hhs__slide-edit-overlay">
											<MediaUpload
												onSelect={(media) => {
													updateSlide(index, "imageId", media.id);
													updateSlide(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												value={slide.imageId}
												render={({ open }) => (
													<Button variant="secondary" onClick={(e) => {
														e.stopPropagation();
														open();
													}} style={{ fontSize: "11px" }}>
														Replace
													</Button>
												)}
											/>
										</div>
									)}
								</div>
								<div className="hhs__slide-meta">
									<RichText
										tagName="strong"
										className="hhs__slide-artist"
										value={slide.artistName}
										onChange={(val) => updateSlide(index, "artistName", val)}
										placeholder="Artist Name"
										onClick={(e) => e.stopPropagation()}
									/>
								</div>
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}
