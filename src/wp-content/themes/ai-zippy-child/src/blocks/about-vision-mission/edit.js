import { useBlockProps, RichText, MediaUpload } from "@wordpress/block-editor";
import { Button, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, visionText, missionText, uspText, graphicImageId, graphicImageUrl, textAlign, visionLabel, missionLabel, uspLabel } = attributes;
	const blockProps = useBlockProps({ className: `abvm has-text-align-${textAlign || "left"}` });

	return (
		<div {...blockProps}>
			<div className="abvm__container">
				<div className="abvm__grid">
					<div className="abvm__content">
						<div className="abvm__header">
							<RichText
								tagName="h2"
								className="abvm__main-title"
								value={title}
								onChange={(val) => setAttributes({ title: val })}
								placeholder="Vision & Mission"
							/>
						</div>

						<div className="abvm__item">
							<RichText
								tagName="span"
								className="abvm__label"
								value={visionLabel || "Vision"}
								onChange={(val) => setAttributes({ visionLabel: val })}
								placeholder="Vision"
							/>
							<RichText
								tagName="p"
								className="abvm__text"
								value={visionText}
								onChange={(val) => setAttributes({ visionText: val })}
								placeholder="Vision description..."
							/>
						</div>

						<div className="abvm__item">
							<RichText
								tagName="span"
								className="abvm__label"
								value={missionLabel || "Mission"}
								onChange={(val) => setAttributes({ missionLabel: val })}
								placeholder="Mission"
							/>
							<RichText
								tagName="p"
								className="abvm__text"
								value={missionText}
								onChange={(val) => setAttributes({ missionText: val })}
								placeholder="Mission description..."
							/>
						</div>

						<div className="abvm__item">
							<RichText
								tagName="span"
								className="abvm__label"
								value={uspLabel || "USP"}
								onChange={(val) => setAttributes({ uspLabel: val })}
								placeholder="USP"
							/>
							<RichText
								tagName="p"
								className="abvm__text"
								value={uspText}
								onChange={(val) => setAttributes({ uspText: val })}
								placeholder="Unique Selling Proposition description..."
							/>
						</div>
					</div>

					<div className="abvm__graphic-wrap">
						{graphicImageUrl ? (
							<div className="abvm__image-preview-wrap">
								<img className="abvm__image-preview" src={graphicImageUrl} alt="" />
								<Button
									variant="secondary"
									isDestructive
									onClick={() => setAttributes({ graphicImageId: 0, graphicImageUrl: "" })}
									style={{ position: "absolute", top: 10, right: 10 }}
								>
									Remove
								</Button>
							</div>
						) : (
							<MediaUpload
								onSelect={(media) => setAttributes({ graphicImageId: media.id, graphicImageUrl: media.url })}
								allowedTypes={["image"]}
								render={({ open }) => (
									<div className="abvm__image-placeholder" onClick={open} role="button" tabIndex={0}>
										<span>+ Upload Graphic Image</span>
									</div>
								)}
							/>
						)}
					</div>
				</div>
			</div>
		</div>
	);
}
