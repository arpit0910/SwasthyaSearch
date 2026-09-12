import React from 'react';
import { Box, Container, Stack as MuiStack, Typography as MuiTypography, Breadcrumbs, Link, Chip, Paper, Alert, AlertTitle, Button } from '@mui/material';
import ArrowForward from '@mui/icons-material/ArrowForward';
import HealthAndSafetyOutlined from '@mui/icons-material/HealthAndSafetyOutlined';

export const config = window.arogioDesign;
export const hi = config.locale === 'hi';
export const t = (en, hindi) => hi ? hindi : en;
export const localized = value => typeof value === 'string' ? value : value?.[config.locale] || value?.en || '';
export const href = (name, params = {}) => {
  const target = new URL(config.routes[name], location.href);
  Object.entries(params).forEach(([key, value]) => {
    if (Array.isArray(value)) value.forEach(v => target.searchParams.append(key.endsWith('[]') ? key : key + '[]', v));
    else if (value != null && value !== '') target.searchParams.set(key, value);
  });
  return target.href;
};
export const safeUrl = value => { if (typeof value !== 'string' || !value.trim()) return null; try { const u = new URL(value, location.href); return ['http:', 'https:'].includes(u.protocol) ? u.href : null; } catch { return null; } };
export function Stack({ sx, gap, alignItems, justifyContent, flexWrap, ...props }) { return <MuiStack {...props} sx={{ minWidth: 0, gap, alignItems, justifyContent, flexWrap, ...sx }} />; }
export function Typography({ sx, fontWeight, ...props }) { return <MuiTypography {...props} sx={{ fontWeight, ...sx }} />; }
export function PageIntro({ title, description, label, trail = [] }) {
  return <Box sx={{ mb: 4 }}><Breadcrumbs sx={{ mb: 2, fontSize: 13 }}><Link href={href('home')} color="inherit" underline="hover">{t('Home', 'होम')}</Link>{trail.map((v, i) => <Typography variant="body2" key={i}>{v}</Typography>)}</Breadcrumbs>{label && <Chip size="small" label={label} sx={{ bgcolor: 'action.hover', color: 'primary.main', mb: 1.5 }} />}<Typography variant="h1" sx={{ fontSize: { xs: 29, md: 38 }, mb: 1 }}>{title}</Typography><Typography color="text.secondary" sx={{ maxWidth: 880, fontSize: 17 }}>{description}</Typography></Box>;
}
export function Page({ children, name }) { return <Box className={`stitch-page stitch-${name}`} sx={{ bgcolor: 'background.default', color: 'text.primary', minHeight: '70vh' }}><Container sx={{ py: { xs: 3, md: 4 } }}>{children}</Container></Box>; }
export function Heading({ children, action, href: link }) { return <Stack direction={{ xs: 'column', sm: 'row' }} alignItems={{ sm: 'center' }} justifyContent="space-between" gap={1} sx={{ mb: 2, mt: 4 }}><Typography variant="h2" sx={{ fontSize: { xs: 23, md: 26 } }}>{children}</Typography>{link && <Button href={link} endIcon={<ArrowForward />}>{action || t('View all', 'सभी देखें')}</Button>}</Stack>; }
export function Advisory() { return <Alert severity="error" icon={<HealthAndSafetyOutlined />} sx={{ mt: 4, py: 2 }}><AlertTitle>{t('Medical information & emergency advisory', 'चिकित्सा जानकारी और आपातकालीन सहायता')}</AlertTitle>{t('Arogio provides healthcare discovery and educational information. It does not provide medical diagnosis, treatment, or emergency response.', 'Arogio स्वास्थ्य सेवाएँ खोजने और जानकारी देने का मंच है। यह निदान, उपचार या आपातकालीन प्रतिक्रिया प्रदान नहीं करता।')}<Box sx={{ mt: 1.5 }}><Button href={href('emergency')} color="error" variant="contained">{t('Emergency resources', 'आपातकालीन संसाधन')}</Button></Box></Alert>; }
export function InfoBlock({ title, children, tone = 'neutral' }) { return <Paper variant="outlined" sx={{ p: 2.5, height: '100%', bgcolor: tone === 'neutral' ? 'action.hover' : undefined }}><Typography variant="h3" sx={{ mb: 1.5 }}>{title}</Typography>{children}</Paper>; }
export function PlainText({ children, ...props }) { return <Typography sx={{ whiteSpace: 'pre-line', overflowWrap: 'anywhere' }} color="text.secondary" {...props}>{children}</Typography>; }
