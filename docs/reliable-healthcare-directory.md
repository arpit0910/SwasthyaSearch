# Reliable Healthcare Directory (Emergency Safe)

## Trusted Source Policy

Only records with all conditions are considered reliable:

- `is_verified = true`
- `source_verification_status = verified`
- `source_confidence_score >= 85`
- critical fields present (city, name, contact, address)

Configured trusted domains:

- `nmc.org.in`
- `medicalcouncil.rajasthan.gov.in`
- `rmc.rajasthan.gov.in`
- `facility.abdm.gov.in`
- `eraktkosh.mohfw.gov.in`
- `naco.gov.in`
- `mohfw.gov.in`

If data from a source cannot be verified, it is skipped.

## API Endpoints

- `GET /api/doctors?city=Jaipur`
- `GET /api/doctors?city=Jaipur&department=Cardiology`
- `GET /api/hospitals?city=Jaipur`
- `GET /api/blood-banks?city=Jaipur`

When no reliable records exist, APIs return an empty `data` array and a clear message.

## Department Mapping Rule

Doctor ingestion uses existing departments already present in DB.

- No dynamic department creation.
- Records with unknown department names are rejected.

## City Handling

City names are normalized (`trim`, title-case), then exact city matching is applied.

## Multi-City Scalability

Logic is city-agnostic. The same API/service path works for any Indian city once reliable records are available.

## Known Limitation

Some official sources are JS-driven and may not expose complete structured data via direct HTML fetch. In such cases, sync may return fewer or zero records rather than generating unsafe placeholders.

