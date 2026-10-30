import { useBlockProps, MediaUpload, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, SelectControl, Button } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
	const { artists = [] } = attributes;
	const [activeArtistIndex, setActiveArtistIndex] = useState(0);
	const blockProps = useBlockProps({ className: "ag-editor" });

	const addArtist = () => {
		const newArtists = [
			...artists,
			{
				name: "New Artist",
				number: String(artists.length + 1).padStart(2, "0"),
				imageId: 0,
				imageUrl: "",
				socials: { facebook: "", instagram: "", tiktok: "", threads: "", youtube: "" },
				realName: "",
				dob: "",
				genre: "",
				overview: "",
				labelPreset: "dao",
				labelLogoId: 0,
				labelLogoUrl: ""
			}
		];
		setAttributes({ artists: newArtists });
		setActiveArtistIndex(newArtists.length - 1);
	};

	const removeArtist = (index) => {
		const newArtists = artists.filter((_, i) => i !== index);
		setAttributes({ artists: newArtists });
		if (activeArtistIndex >= newArtists.length) {
			setActiveArtistIndex(Math.max(0, newArtists.length - 1));
		}
	};

	const updateArtist = (index, key, value) => {
		const newArtists = [...artists];
		newArtists[index] = { ...newArtists[index], [key]: value };
		setAttributes({ artists: newArtists });
	};

	const updateArtistSocial = (index, socialKey, value) => {
		const newArtists = [...artists];
		const currentSocials = newArtists[index].socials || {};
		newArtists[index] = {
			...newArtists[index],
			socials: {
				...currentSocials,
				[socialKey]: value
			}
		};
		setAttributes({ artists: newArtists });
	};

	const activeArtist = artists[activeArtistIndex];

	return (
		<>
			<InspectorControls>
				{activeArtist && (
					<PanelBody title={`Edit: ${activeArtist.name || "Unnamed"}`} initialOpen={true}>
						<TextControl
							label="Stage Name"
							value={activeArtist.name}
							onChange={(val) => updateArtist(activeArtistIndex, "name", val)}
						/>
						<TextControl
							label="Number (e.g. 01, 02)"
							value={activeArtist.number}
							onChange={(val) => updateArtist(activeArtistIndex, "number", val)}
						/>
						
						<h4 style={{ margin: "20px 0 10px", borderBottom: "1px solid #eee", paddingBottom: 5 }}>Bio & Details (Popup)</h4>
						<TextControl
							label="Real Name"
							value={activeArtist.realName}
							onChange={(val) => updateArtist(activeArtistIndex, "realName", val)}
						/>
						<TextControl
							label="Date of Birth"
							value={activeArtist.dob}
							onChange={(val) => updateArtist(activeArtistIndex, "dob", val)}
						/>
						<TextControl
							label="Genre(s)"
							value={activeArtist.genre}
							onChange={(val) => updateArtist(activeArtistIndex, "genre", val)}
						/>
						<TextareaControl
							label="Overview / Bio"
							value={activeArtist.overview}
							onChange={(val) => updateArtist(activeArtistIndex, "overview", val)}
							rows={6}
						/>

						<SelectControl
							label="Signed Label Preset Logo"
							value={activeArtist.labelPreset || "dao"}
							options={[
								{ label: "DAO Music Ent", value: "dao" },
								{ label: "MaiDao", value: "maidao" },
								{ label: "Publishing", value: "publishing" },
								{ label: "Custom Upload Logo", value: "custom" },
								{ label: "None / Hide", value: "none" }
							]}
							onChange={(val) => updateArtist(activeArtistIndex, "labelPreset", val)}
						/>

						{activeArtist.labelPreset === "custom" && (
							<div style={{ marginBottom: 15 }}>
								<label style={{ display: "block", marginBottom: 5 }}>Custom Label Logo</label>
								{activeArtist.labelLogoUrl ? (
									<div style={{ marginBottom: 10 }}>
										<img src={activeArtist.labelLogoUrl} style={{ maxHeight: 40, backgroundColor: "#333", padding: 5 }} alt="" />
										<Button
											isDestructive
											variant="link"
											onClick={() => {
												updateArtist(activeArtistIndex, "labelLogoId", 0);
												updateArtist(activeArtistIndex, "labelLogoUrl", "");
											}}
										>
											Remove
										</Button>
									</div>
								) : (
									<MediaUpload
										onSelect={(media) => {
											updateArtist(activeArtistIndex, "labelLogoId", media.id);
											updateArtist(activeArtistIndex, "labelLogoUrl", media.url);
										}}
										allowedTypes={["image"]}
										render={({ open }) => (
											<Button variant="secondary" onClick={open}>
												Select Logo Image
											</Button>
										)}
									/>
								)}
							</div>
						)}

						<h4 style={{ margin: "20px 0 10px", borderBottom: "1px solid #eee", paddingBottom: 5 }}>Social Links</h4>
						<TextControl
							label="Facebook URL"
							value={activeArtist.socials?.facebook}
							onChange={(val) => updateArtistSocial(activeArtistIndex, "facebook", val)}
						/>
						<TextControl
							label="Instagram URL"
							value={activeArtist.socials?.instagram}
							onChange={(val) => updateArtistSocial(activeArtistIndex, "instagram", val)}
						/>
						<TextControl
							label="TikTok URL"
							value={activeArtist.socials?.tiktok}
							onChange={(val) => updateArtistSocial(activeArtistIndex, "tiktok", val)}
						/>
						<TextControl
							label="Threads URL"
							value={activeArtist.socials?.threads}
							onChange={(val) => updateArtistSocial(activeArtistIndex, "threads", val)}
						/>
						<TextControl
							label="YouTube URL"
							value={activeArtist.socials?.youtube}
							onChange={(val) => updateArtistSocial(activeArtistIndex, "youtube", val)}
						/>

						<div style={{ marginTop: 25, borderTop: "1px solid #ccc", paddingTop: 15 }}>
							<Button isDestructive style={{ width: "100%" }} onClick={() => removeArtist(activeArtistIndex)}>
								Delete This Artist
							</Button>
						</div>
					</PanelBody>
				)}
				<PanelBody title="Quick List & Reorder" initialOpen={false}>
					{artists.map((artist, idx) => (
						<div
							key={idx}
							onClick={() => setActiveArtistIndex(idx)}
							style={{
								padding: "10px",
								border: activeArtistIndex === idx ? "2px solid #EC4600" : "1px solid #eee",
								borderRadius: 4,
								marginBottom: 8,
								cursor: "pointer",
								background: activeArtistIndex === idx ? "#fff0ea" : "#fff",
								display: "flex",
								alignItems: "center",
								justifyContent: "space-between"
							}}
						>
							<span>{artist.number} - {artist.name || "Unnamed"}</span>
							<Button
								isDestructive
								variant="link"
								onClick={(e) => {
									e.stopPropagation();
									removeArtist(idx);
								}}
							>
								✕
							</Button>
						</div>
					))}
					<Button variant="primary" style={{ width: "100%", marginTop: 10 }} onClick={addArtist}>
						+ Add Artist
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 20 }}>
					<h3 style={{ margin: 0, color: "#fff" }}>Artists Grid Editor</h3>
					<Button variant="primary" onClick={addArtist}>
						+ Add New Artist
					</Button>
				</div>

				<div className="age__grid">
					{artists.map((artist, index) => {
						const isActive = activeArtistIndex === index;
						return (
							<div
								key={index}
								className={`age__card ${isActive ? "age__card--active" : ""}`}
								onClick={() => setActiveArtistIndex(index)}
							>
								<div className="age__card-img-wrap">
									{artist.imageUrl ? (
										<img className="age__card-img" src={artist.imageUrl} alt="" />
									) : (
										<div className="age__card-placeholder">
											<MediaUpload
												onSelect={(media) => {
													updateArtist(index, "imageId", media.id);
													updateArtist(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												render={({ open }) => (
													<Button variant="secondary" onClick={open}>
														Add Image
													</Button>
												)}
											/>
										</div>
									)}

									{artist.imageUrl && (
										<div className="age__image-edit-overlay" onClick={(e) => e.stopPropagation()}>
											<MediaUpload
												onSelect={(media) => {
													updateArtist(index, "imageId", media.id);
													updateArtist(index, "imageUrl", media.url);
												}}
												allowedTypes={["image"]}
												value={artist.imageId}
												render={({ open }) => (
													<Button variant="secondary" onClick={open} style={{ fontSize: "11px" }}>
														Replace Image
													</Button>
												)}
											/>
										</div>
									)}
								</div>
								<div className="age__card-info">
									<span className="age__card-number">{artist.number}</span>
									<span className="age__card-name">{artist.name || "Unnamed"}</span>
								</div>
							</div>
						);
					})}
				</div>
			</div>
		</>
	);
}
