import { useBlockProps, RichText, MediaUpload, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button, Dashicon, SelectControl, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subheading, artists, textAlign, layout, titleAccent, description, dividerText, note, dialogEyebrow, pendingText, dialogFallback } = attributes;
	const blockProps = useBlockProps({ className: `as has-text-align-${textAlign || "left"}` });

	const addArtist = () => {
		const newArtists = [
			...artists,
			{
				name: "New Artist",
				number: String(artists.length + 1).padStart(2, "0"),
				imageId: 0,
				imageUrl: "",
				dob: "",
				genre: "",
				bio: "",
				socials: { facebook: "", instagram: "", tiktok: "", threads: "", youtube: "" }
			}
		];
		setAttributes({ artists: newArtists });
	};

	const removeArtist = (index) => {
		const newArtists = artists.filter((_, i) => i !== index);
		setAttributes({ artists: newArtists });
	};

	const updateArtist = (index, key, value) => {
		const newArtists = [...artists];
		newArtists[index] = { ...newArtists[index], [key]: value };
		setAttributes({ artists: newArtists });
	};

	const updateArtistSocial = (index, socialKey, value) => {
		const newArtists = [...artists];
		newArtists[index] = {
			...newArtists[index],
			socials: {
				...newArtists[index].socials,
				[socialKey]: value
			}
		};
		setAttributes({ artists: newArtists });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Layout" initialOpen={true}>
					<SelectControl
						label="Layout"
						value={layout || "grid"}
						options={[
							{ label: "Grid", value: "grid" },
							{ label: "Bức tường danh vọng (Wall of Honor)", value: "wall" }
						]}
						onChange={(val) => setAttributes({ layout: val })}
					/>
				</PanelBody>
				{layout === "wall" && (
					<PanelBody title="Bức tường danh vọng — chữ" initialOpen={true}>
						<TextControl label="Tiêu đề (dòng 1)" value={title} onChange={(val) => setAttributes({ title: val })} />
						<TextControl label="Tiêu đề (dòng 2, màu vàng)" value={titleAccent || ""} onChange={(val) => setAttributes({ titleAccent: val })} />
						<TextareaControl label="Mô tả bên phải (dùng <br> để xuống dòng)" value={description || ""} onChange={(val) => setAttributes({ description: val })} rows={3} />
						<TextControl label="Chữ phân cách" value={dividerText || ""} onChange={(val) => setAttributes({ dividerText: val })} />
						<TextControl label="Dòng ghi chú cuối" value={note || ""} onChange={(val) => setAttributes({ note: val })} />
						<TextControl label="Nhãn trong hộp giới thiệu" value={dialogEyebrow || ""} onChange={(val) => setAttributes({ dialogEyebrow: val })} />
						<TextControl label="Nội dung hộp giới thiệu khi chưa có bio" value={dialogFallback || ""} onChange={(val) => setAttributes({ dialogFallback: val })} />
						<TextControl label="Chữ khi chưa có chân dung" value={pendingText || ""} onChange={(val) => setAttributes({ pendingText: val })} />
					</PanelBody>
				)}
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
				<PanelBody title="Manage Artists Info" initialOpen={true}>
					{artists.map((artist, index) => (
						<div key={index} style={{ borderBottom: "1px solid #ccc", paddingBottom: 15, marginBottom: 15 }}>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>Artist #{index + 1}</strong>
								<Button isDestructive variant="link" onClick={() => removeArtist(index)}>
									Remove
								</Button>
							</div>
							<TextControl
								label="Vị trí ảnh (object-position, vd 50% 42%)"
								value={artist.position || ""}
								onChange={(val) => updateArtist(index, "position", val)}
							/>
							<TextControl
								label="Number"
								value={artist.number}
								onChange={(val) => updateArtist(index, "number", val)}
							/>
							<TextareaControl
								label="Biography / Info"
								value={artist.bio || ""}
								onChange={(val) => updateArtist(index, "bio", val)}
								rows={4}
							/>
							<TextControl
								label="Date of Birth (DOB)"
								value={artist.dob || ""}
								onChange={(val) => updateArtist(index, "dob", val)}
							/>
							<TextControl
								label="Genre(s)"
								value={artist.genre || ""}
								onChange={(val) => updateArtist(index, "genre", val)}
							/>
							<TextControl
								label="Facebook URL"
								value={artist.socials?.facebook}
								onChange={(val) => updateArtistSocial(index, "facebook", val)}
							/>
							<TextControl
								label="Instagram URL"
								value={artist.socials?.instagram}
								onChange={(val) => updateArtistSocial(index, "instagram", val)}
							/>
							<TextControl
								label="TikTok URL"
								value={artist.socials?.tiktok}
								onChange={(val) => updateArtistSocial(index, "tiktok", val)}
							/>
							<TextControl
								label="Threads URL"
								value={artist.socials?.threads}
								onChange={(val) => updateArtistSocial(index, "threads", val)}
							/>
							<TextControl
								label="YouTube URL"
								value={artist.socials?.youtube}
								onChange={(val) => updateArtistSocial(index, "youtube", val)}
							/>
						</div>
					))}
					<Button variant="primary" onClick={addArtist} style={{ width: "100%", marginTop: 10 }}>
						Add New Artist
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="as__container">
					<div className="as__header-wrap">
						<div className="as__intro">
							<div className="as__subheading-wrap">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ transform: "rotate(45deg)", marginRight: 8 }}>
									<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
								</svg>
								<RichText
									tagName="h6"
									className="as__subheading"
									value={subheading}
									onChange={(val) => setAttributes({ subheading: val })}
									placeholder="Subheading..."
								/>
							</div>
							<RichText
								tagName="h2"
								className="as__heading"
								value={title}
								onChange={(val) => setAttributes({ title: val })}
								placeholder="Title..."
							/>
						</div>
						<Button variant="primary" onClick={addArtist}>
							Add Artist Card
						</Button>
					</div>

					{/* Artists list editing */}
					<div className="as__grid">
						{artists.map((artist, index) => (
							<div key={index} className="as__card">
								<div className="as__card-img-wrap">
									{artist.imageUrl ? (
										<img className="as__card-img" src={artist.imageUrl} alt="" />
									) : (
										<div className="as__card-placeholder">
											<MediaUpload
												onSelect={(media) => {
													updateArtist(index, "imageId", media.id);
													updateArtist(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												render={({ open }) => (
													<Button variant="secondary" onClick={open}>
														Select Image
													</Button>
												)}
											/>
										</div>
									)}
									{artist.imageUrl && (
										<div className="as__image-edit-overlay">
											<MediaUpload
												onSelect={(media) => {
													updateArtist(index, "imageId", media.id);
													updateArtist(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												value={artist.imageId}
												render={({ open }) => (
													<Button variant="secondary" onClick={open} style={{ fontSize: "11px" }}>
														Replace
													</Button>
												)}
											/>
										</div>
									)}
								</div>

								<div className="as__card-info">
									<div className="as__card-meta">
										<span className="as__card-number">{artist.number}</span>
									</div>
									<RichText
										tagName="h3"
										className="as__card-name"
										value={artist.name}
										onChange={(val) => updateArtist(index, "name", val)}
										placeholder="Artist Name"
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
