# Arogio — Complete Google Stitch Redesign Prompt

Copy the prompt below into Google Stitch. It covers the public website and administration area, using the existing Arogio project as its functional foundation.

---

Redesign the complete Arogio website as a cohesive, polished, responsive healthcare discovery and health-information platform for India.

This is a complete product redesign, including public pages, interactive tools, shared components, and administration screens. Do not stop at a landing page.

Use the existing product context below as the functional foundation. The visual direction and information architecture described here are proposed improvements; preserve existing capabilities while making them easier to discover and use.

## 1. PRODUCT CONTEXT

Public brand: Arogio.
Existing development project name: SwasthyaSearch.
Use “Arogio” throughout the interface.

Arogio helps people:
- Find doctors, hospitals, clinics, and blood banks.
- Search healthcare information using everyday language.
- Explore symptoms and relevant medical specialties.
- Ask health questions through Jeeva, the website’s assistant.
- Read medicine information and health articles.
- Explore “Nani Dadi Ke Nuskhe,” a library of traditional home remedies with evidence and safety information.
- Use wellness activities and health quizzes.
- Request consultations and enter video consultation rooms.
- Suggest missing healthcare providers and report incorrect information.

The platform is positioned as free and ad-free for healthcare discovery.

Primary audiences:
- Patients and family caregivers.
- People seeking nearby healthcare in Indian cities.
- English-speaking and Hindi-speaking users.
- Users with limited technical familiarity.
- People browsing on affordable mobile devices and slower connections.
- Administrators managing directory records and health content.

The website must feel calm, understandable, credible, and approachable.

Arogio supports healthcare discovery and information. Do not present the assistant, symptom checker, or home remedies as a substitute for professional diagnosis or emergency services.

## 2. MANDATORY MATERIAL UI FOUNDATION

Use this library as the source of interface components:
https://mui.com/material-ui/all-components/

Design with actual Material UI component structure, proportions, interaction patterns, and states.

Use a customized, consistent MUI theme so the product has its own identity.

All standard controls, navigation, forms, cards, dialogs, feedback, and layouts should map to Material UI components. Bespoke illustrations and activity visuals may sit inside MUI layouts.

Do not mix in Bootstrap, shadcn, Ant Design, or unrelated component systems.

Prefer the core Material UI library. If an advanced feature requires MUI X or another dependency, identify it explicitly and provide a core-MUI alternative where practical. Do not silently depend on paid components.

If generating implementation code, use React and Material UI components. The existing application uses Laravel, Blade templates, and server endpoints; React integration is a separate implementation step. Preserve the existing backend concepts and URL structure. Do not imply that a visual prototype already implements the Laravel backend.

## 3. DESIGN DIRECTION

Create a modern healthcare product with strong readability and restrained styling.

Suggested theme:
- Primary: deep teal, approximately #00796B.
- Secondary: blue, approximately #1565C0.
- Main background: soft off-white, approximately #F7FAFA.
- Surface: white.
- Primary text: deep slate, approximately #172B3A.
- Secondary text: muted slate.
- Error and urgent states: red.
- Caution states: amber.
- Success states: green.
- Subtle teal-tinted surfaces for selected and informational sections.

Validate text contrast instead of assuming every palette combination is accessible.

Use:
- An 8px spacing system.
- Approximately 12–16px card corners.
- Approximately 8–12px control corners.
- Subtle borders and low elevation.
- Comfortable body text, usually 16px.
- Clear typography hierarchy.
- Roboto or a similarly readable font, with Noto Sans Devanagari or equivalent Hindi support.
- Material icons with one consistent visual style.
- A content width around 1200–1280px on desktop.
- Short, purposeful transitions and reduced-motion support.

Avoid excessive gradients, glass effects, oversized shadows, decorative dashboard clutter, and giant hero sections that push useful actions below the fold.

Retain the Arogio identity. Existing logo assets are named:
- arogio-logo.png
- arogio-logo-dark.png
- fav-icon.png

If those files are not available in the design environment, use a clearly identified wordmark placeholder.

Provide light and dark themes, with light as the primary presentation.

## 4. GLOBAL INFORMATION ARCHITECTURE

Organize the public navigation into understandable groups:

Find Care:
- Doctors
- Hospitals & Clinics
- Blood Banks
- Departments

Health Information:
- Diseases
- Medicines
- Health Articles
- Nani Dadi Ke Nuskhe

Wellness:
- Activities
- Health Quizzes

Consultations:
- Request a consultation
- Enter a video consultation

Additional global access:
- Jeeva assistant
- Emergency help
- English / हिंदी language switch
- Light / dark theme switch
- Selected city
- About and Contact

Use compact desktop navigation and a mobile Drawer.

A proposed mobile BottomNavigation can contain Home, Find Care, Jeeva, and More. Ensure it does not collide with forms, dialogs, or the floating assistant.

The footer should include:
- Brief platform description.
- Directory links.
- Health and wellness links.
- Suggest a provider.
- Contact.
- Privacy Policy.
- Terms of Service.
- Healthcare-information disclaimer.
- Free, ad-free positioning.

Do not require a patient account to browse the public website. Do not invent patient dashboards, subscriptions, payments, or medical-record storage.

## 5. HOMEPAGE — /

Build an actionable homepage with this hierarchy:

A. Compact header.

B. Search-focused hero:
Suggested headline: “Find the right care, closer to you.”
Supporting text should explain that users can find healthcare providers and understandable health information.

Include:
- City selector.
- “Use my location” action with manual fallback.
- Main search field.
- Clear search action.
- A distinction between finding providers and asking a health question.

Example search prompts:
- “Cardiologist in Jaipur”
- “Hospitals near me”
- “Blood bank in Delhi”
- “Search medicine information”

C. Quick-access services:
Doctors, Hospitals, Blood Banks, Symptom Check, Medicines, and Home Remedies.

D. Jeeva introduction:
A short explanation, suggested questions, and “Ask Jeeva.”

E. Browse medical departments:
Compact specialty tiles linked to relevant discovery flows.

F. Urgent help:
A visible, concise route to emergency resources.

G. Health knowledge:
Recent or featured articles and a small preview of medicine information.

H. Nani Dadi Ke Nuskhe:
A dedicated preview showing traditional remedies alongside evidence and safety labels.

I. Wellness:
Breathing, grounding, mood check-in, calming audio, games, and quizzes.

J. Consultation:
A compact explanation and request entry point.

K. Community contribution:
Suggest a doctor or hospital.

L. Feedback:
A concise form or expandable section.

Avoid displaying every feature as an equally prominent large card. Prioritize healthcare discovery and use progressive disclosure for secondary content.

Keep health-update signup voluntary and dismissible. It must not block browsing or emergency access.

## 6. SEARCH AND LOCATION EXPERIENCE

Support search by provider name, specialty, city, symptoms, and health-information terms where supported by the existing backend.

Design:
- Autocomplete suggestions grouped by relevant result type.
- Selected city shown clearly.
- Manual location entry.
- Location permission explanation.
- Permission denied, unavailable location, loading, and retry states.
- Search results with relevant category grouping.
- Applied filters that remain visible and removable.
- Clear query and reset filters actions.
- No-results suggestions.

Preserve selected city consistently across relevant pages.

Do not show distance unless usable location data exists. Never imply that missing records mean no healthcare exists in that city.

## 7. DIRECTORY SCREENS

Existing routes:
- /doctors
- /hospitals
- /hospitals/{hospital}/doctors
- /blood-banks

Use a shared directory design:
- Page heading and short description.
- Search and location controls.
- Desktop filter sidebar.
- Mobile filter Drawer.
- Applied-filter chips.
- Result count.
- Consistent listing cards.
- Pagination.
- Loading, empty, error, and incomplete-data states.

Doctor filters:
- Name or keyword.
- Department/specialty.
- Experience.
- City.
- Nearby discovery when location is available.

Doctor cards:
- Name.
- Avatar or neutral fallback.
- Specialty.
- Qualifications when available.
- Experience when available.
- Hospital or clinic association.
- Address and city.
- Available contact numbers.
- Directions.
- Supported listing-feedback actions.

Hospital and clinic filters:
- Name or keyword.
- Facility type.
- City.
- Available benefit/scheme filters.

Hospital cards:
- Name.
- Facility type.
- Address and landmark.
- Contact numbers.
- Directions.
- Associated doctors action.
- Benefits or scheme acceptance, including ESIC where recorded.

Blood-bank filters:
- Name or keyword.
- Blood group.
- Facility.
- City.
- Location where supported.

Blood-bank cards:
- Name.
- Address.
- Contact.
- Available facility details.
- Directions.
- Clear instruction to call and confirm availability.

Do not portray blood inventory as live unless the data explicitly supports it.

Use consistent “Report incorrect information” dialogs. Existing community listing votes or feedback should remain distinguishable from clinical ratings and official verification.

Do not fabricate provider ratings, credentials, fees, availability, verification badges, or appointment slots.

If deeper provider information is needed without an existing detail route, use an expandable section or detail Drawer. Label new standalone detail pages as proposed additions.

## 8. DIRECTORY TRUST AND DATA QUALITY

Represent these conditions honestly:
- Verified record.
- Record with incomplete information.
- No reliable results.
- Contact information unavailable.
- Source information available.
- Last verification date available.

The project includes a stricter reliable-directory policy requiring verified source status, a confidence threshold, and essential contact/address fields. Do not apply a “Verified” badge indiscriminately to every listing.

Show source and verification details only when supplied by the backend.

Use language such as “Call before visiting.”

Any prototype provider data must be clearly identified as sample data. Do not invent real-looking contact numbers for healthcare providers.

## 9. JEEVA ASSISTANT

Jeeva is Arogio’s health-information and healthcare-discovery assistant.

Design:
- A clearly labeled launcher.
- Desktop side panel.
- Mobile full-screen conversation.
- Welcome state.
- City selection.
- Suggested questions.
- User and assistant messages.
- Typing/loading state.
- Input composer.
- Retry and failure-report actions.
- Clear conversation controls.

Assistant responses may include:
- General health information.
- Relevant specialties.
- Doctor, hospital, and blood-bank cards.
- Related articles.
- Links to the symptom-checking flow.

Use readable paragraphs and compact structured result cards.

Explain Jeeva’s role in plain language. Do not describe it as a doctor or claim diagnostic certainty.

Provide a clear urgent-help pathway when appropriate, without making the assistant appear to be an emergency-response service.

## 10. SYMPTOM CHECK — /symptom-test

Create a guided flow:

Step 1: Basic information.
- Age.
- Gender, including an unspecified option aligned with backend support.

Step 2: Symptoms.
- Searchable multiple selection.
- Selected symptom chips.
- Free-text symptom description.
- Clear validation.

Step 3: Review and submit.

Step 4: Results.
- Summary of entered information.
- Possible relevant conditions, framed as informational matches.
- Relevant medical specialties.
- Suggested next actions.
- Find relevant providers.
- Edit answers and restart.

The existing backend supports age, gender, symptom selection, and free text. Do not add mandatory clinical inputs requiring new backend logic without marking them as proposed enhancements.

Do not display matching scores as validated diagnostic probabilities.

## 11. MEDICINE INFORMATION

Routes:
- /medicines
- /medicines/{slug}

Index:
- Search by medicine, brand, generic name, or composition.
- Clear result cards.
- Name, generic name, strength/type when available.
- Prescription-required indicator when recorded.
- Short purpose summary.

Detail:
- Name and generic name.
- Brand names.
- Composition and strength.
- Medicine type and category.
- Overview and uses.
- Benefits and mechanism.
- Existing dosage-information content, clearly educational.
- Common and serious side effects.
- Drug, food, and alcohol interactions.
- Pregnancy and breastfeeding information.
- Kidney, liver, driving, and allergy warnings.
- Precautions and contraindications.
- Missed dose, overdose, and storage information.
- When to contact a doctor.
- FAQs.
- References.
- Review status and review date when available.
- Related medicines and articles.
- Report incorrect information form.

Use a readable article layout with a desktop contents sidebar and mobile section navigation.

Keep critical warnings visible. Do not hide every warning in collapsed content.

This is an information library. Do not add purchasing, prescriptions, or shopping-cart flows.

## 12. NANI DADI KE NUSKHE

Routes:
- /nani-dadi-ke-nuskhe
- /nani-dadi-ke-nuskhe/category/{slug}
- /nani-dadi-ke-nuskhe/ingredient/{slug}
- /nani-dadi-ke-nuskhe/{slug}

Preserve this culturally meaningful feature name and support Hindi presentation.

Index:
- Search by problem, remedy, or ingredient.
- Category navigation.
- Ingredient browsing.
- Featured remedies.
- Clear evidence labels.

Detail:
- Remedy title and intended concern.
- Short explanation.
- Ingredients and quantities.
- Preparation.
- Step-by-step use.
- Frequency and duration when supplied.
- Traditional benefit.
- How it may help.
- Evidence summary.
- Suitable and unsuitable audiences.
- Children, pregnancy, elderly, and medical-condition warnings.
- Medicine interactions.
- Possible side effects.
- Red flags.
- When to see a doctor.
- References.
- Reviewer and review date when available.
- Relevant specialty/provider link.

Supported evidence levels:
Traditional Use, Limited Evidence, Some Supporting Evidence, Moderate Evidence, Strong Evidence, Not Recommended.

Public content must respect the existing publication and medical-review eligibility rules.

Use warm, restrained ingredient imagery without implying that traditional or natural automatically means safe or effective.

## 13. ARTICLES, DISEASES, AND DEPARTMENTS

Routes:
- /articles
- /articles/{article}
- /diseases
- /departments

Articles:
- Search/browse interface.
- Editorial cards.
- Readable article detail.
- Author and available publication metadata.
- Relevant imagery.
- Related content.
- Existing comments submission flow with clear validation and submission feedback.

Diseases:
- Searchable condition information.
- Plain-language descriptions.
- Symptoms and relevant departments when supplied.
- Links to related healthcare discovery.

Departments:
- Searchable specialty directory.
- Friendly specialty icons.
- Short explanations.
- Links to relevant doctors and hospitals.

Use breadcrumbs and consistent reading layouts.

## 14. WELLNESS ACTIVITIES AND QUIZZES

Routes:
- /activities
- /activities/breathing
- /activities/grounding
- /activities/mood-check
- /activities/calm-audio
- /activities/games/memory
- /activities/games/calm-tap
- /quizzes
- /quizzes/{slug}
- Quiz result flow.

Activities hub:
Explain each activity briefly, with a clear action.

Breathing:
- Gentle paced visual.
- Start, pause, reset.
- Text instructions and reduced-motion alternative.

Grounding:
- Guided sensory steps.
- Clear progress and next/back actions.

Mood check-in:
- Accessible mood choices.
- Supportive response and suitable activity links.
- Do not imply persistent private journaling unless implemented.

Calm audio:
- Track choices.
- Play/pause.
- Volume.
- Loading and playback failure states.
- No autoplay.

Memory game:
- Legible matching cards.
- Progress.
- Restart.

Calm-tap game:
- Gentle interaction.
- Start/stop.
- No stressful countdown framing or flashing visuals.

Quizzes:
- Index.
- Question flow.
- Answer options.
- Progress.
- Results.
- Explanations where content supports them.
- Retry and related learning links.

## 15. CONSULTATIONS

Routes:
- /consultations
- /consultations/{uuid}
- Existing consultation-request form flow.

Keep two distinct concepts clear:

A. Consultation request:
- Patient/contact details supported by the form.
- Preferred date and time.
- Reason or symptoms.
- Validation.
- Submission acknowledgement.
- Explain that a request is not automatically a confirmed appointment.

B. Video consultation:
- Patient-name entry.
- Create/enter room.
- Camera and microphone permissions.
- Local video preview.
- Waiting for acceptance.
- Accepted, rejected, connecting, disconnected, and ended states.
- Main participant video.
- Local preview.
- Microphone and camera controls.
- Chat panel.
- End consultation.
- Permission failure and connection recovery.

Do not promise immediate clinician availability or invent payments and scheduling inventory.

## 16. SUPPORT, COMMUNITY, AND LEGAL PAGES

Include:
- /about
- /contact
- /suggest
- /emergency
- /support/crisis
- /privacy-policy
- /terms-of-service
- 404 page.

About:
Mission, audience, discovery purpose, and data transparency.

Contact:
Purpose-based contact form, validation, and success feedback.

Suggest a provider:
Doctor / hospital selection.
Conditional fields appropriate to the selected type.
Explain that submissions are reviewed before publication.

Feedback:
Name, rating, category, and comments.
Use rating for website feedback, not invented provider reviews.

Emergency and crisis:
Prioritize readable, immediate actions and relevant resource links.
Use verified, configurable contact information supplied by the project.
Do not invent helpline numbers or service hours.
Keep these pages free of distracting promotion.

Legal:
Readable document layouts with section navigation.

404:
Clear explanation, home link, and useful discovery links.

Include a reusable maintenance/coming-soon state, without making it the default public experience.

## 17. ADMINISTRATION

Design a separate authenticated administration experience using the same theme with denser layouts.

Include:
- Admin login.
- Dashboard.
- Doctors management.
- Hospitals management.
- Blood banks management.
- Departments.
- Diseases.
- Articles.
- Medicines.
- Medicine correction reports.
- Quizzes.
- FAQs.
- General medical Q&A.
- Cached medical questions.
- Symptom-test reports.
- Consultation requests and rooms.
- Community submissions.
- Home remedies, categories, and ingredients.

Shared admin patterns:
- Searchable, paginated tables.
- Filters.
- Create/edit forms.
- English and Hindi content fields.
- Status indicators.
- Delete confirmation.
- Import/export where supported.
- CSV/XLSX template download where supported.
- Import validation and errors.
- Directory sync progress and results.
- Publication/review controls.
- Accept/reject community submissions.
- Accept/reject consultations.

For home-remedy imports, accommodate existing create-only, create/update, and update-only modes.

Use core MUI Table and TablePagination by default. Treat advanced grids and chart packages as separately identified dependencies.

Use clearly labeled sample dashboard values instead of fabricated operational statistics.

## 18. RESPONSIVE AND ACCESSIBLE BEHAVIOR

Create desktop, tablet, and mobile layouts.

Show representative screens around:
- 1440px desktop.
- 768px tablet.
- 390px mobile.

Requirements:
- No horizontal overflow.
- Touch targets around 44px.
- Visible labels rather than placeholder-only forms.
- Keyboard navigation.
- Visible focus states.
- Logical reading and tab order.
- Accessible dialog focus behavior.
- Text and icons alongside semantic colors.
- Sufficient contrast.
- Correct Hindi rendering without corrupted characters.
- Room for longer Hindi labels.
- Language switching that preserves page context.
- Persistent theme preference.
- Reduced-motion support.
- No hover-only essential controls.
- No floating controls obscuring primary actions.

Use clear English and natural Hindi. Avoid unnecessary medical jargon.

## 19. SHARED COMPONENTS AND STATES

Create a reusable component sheet covering:
- Public header and footer.
- Admin shell.
- Search and city selection.
- Service tile.
- Provider card variants.
- Verification/source indicator.
- Filter panel.
- Article and remedy cards.
- Medicine summary card.
- Assistant message and embedded result.
- Form section.
- Review/status indicator.
- Empty state.
- Error state.
- Loading state.
- Confirmation dialog.
- Mobile navigation.

Show default, hover, focus, selected, disabled, loading, validation-error, and success states where relevant.

Use inline feedback for field errors and durable page states for important outcomes. Reserve transient notifications for noncritical feedback.

## 20. DELIVERABLE AND QUALITY BAR

Produce a coherent multi-screen design, not unrelated screen concepts.

Start with the theme and shared components, then apply them consistently across the full screen inventory.

Prioritize detailed designs for:
- Homepage.
- Doctor directory.
- Hospital directory.
- Blood-bank directory.
- Jeeva conversation.
- Symptom-check flow and results.
- Medicine detail.
- Home-remedy index and detail.
- Article detail.
- Wellness activity.
- Quiz and results.
- Consultation request.
- Video room.
- Admin dashboard, record list, and edit form.

Then complete the remaining listed pages using the shared system.

Demonstrate connected user journeys:
- Home → select city → find doctor → filter → call/directions.
- Home → symptoms → informational results → relevant specialty.
- Home → Jeeva → provider or article result.
- Home remedies → category/ingredient → remedy → safety information.
- Consultation request → acknowledgement.
- Video room → waiting → connected → ended.
- Suggest provider → validation → review acknowledgement.

Keep existing functionality recognizable while improving navigation, readability, density, and interaction consistency.

Use realistic but explicitly labeled sample content where live data is unavailable. Avoid lorem ipsum, fabricated medical claims, false verification, and decorative controls with no defined behavior.

If the design environment cannot implement real React/MUI components, still produce designs explicitly mapped to Material UI and state that implementation remains to be done.

The finished product should feel like a trustworthy Indian healthcare platform: easy to search, comfortable to read, culturally aware, accessible in English and Hindi, and consistent from the first search through administration.
