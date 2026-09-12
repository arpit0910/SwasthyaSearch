import React, { useState, useEffect, useRef } from 'react';
import { Box, Paper, Button, IconButton, Chip, Alert, TextField, InputAdornment, CircularProgress, Tooltip, Dialog, DialogTitle, DialogContent, DialogActions } from '@mui/material';
import Leaf from '@mui/icons-material/SpaOutlined';
import Robot from '@mui/icons-material/SmartToyOutlined';
import Refresh from '@mui/icons-material/Refresh';
import Minimize from '@mui/icons-material/Remove';
import Close from '@mui/icons-material/Close';
import Send from '@mui/icons-material/SendOutlined';
import Mic from '@mui/icons-material/MicNone';
import Place from '@mui/icons-material/NearMeOutlined';
import Volume from '@mui/icons-material/VolumeUpOutlined';
import { config, t, localized, href, safeUrl, Stack, Typography } from './shared';

function initialCity() { try { return localStorage.getItem('arogio_selected_city') || config.city; } catch { return config.city; } }
function ResourceCards({ message }) {
  return <>{[['doctors', 'doctors.index'], ['hospitals', 'hospitals.index'], ['blood_banks', 'blood_banks.index'], ['articles', 'articles.index']].map(([key, route]) => (Array.isArray(message[key]) ? message[key] : []).slice(0, 4).map((item, index) => {
    const name = localized(item.name) || localized(item.title) || [item.first_name, item.last_name].filter(Boolean).join(' ') || t('View information', 'जानकारी देखें');
    const link = safeUrl(item.url || item.link) || href(route, { search: name });
    return <Paper key={key + '-' + index} variant="outlined" sx={{ p: 1.5, mt: 1.5 }}><Typography variant="body2" fontWeight={700}>{name}</Typography><Typography variant="caption" color="text.secondary">{localized(item.department?.name) || item.specialization_summary || item.city || ''}</Typography><Button size="small" href={link} sx={{ display: 'flex' }}>{t('View details', 'जानकारी देखें')}</Button></Paper>;
  }))}</>;
}
export function Jeeva() {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState([]);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');
  const [last, setLast] = useState('');
  const [city, setCity] = useState(initialCity);
  const [cityDialog, setCityDialog] = useState(false);
  const [cityInput, setCityInput] = useState(city);
  const [listening, setListening] = useState(false);
  const input = useRef(null);
  const scroll = useRef(null);
  const controller = useRef(null);
  const recognition = useRef(null);
  const token = useRef(null);
  const pendingText = useRef('');
  useEffect(() => {
    const handler = event => {
      setOpen(current => event.detail?.open ?? !current);
      if (typeof event.detail?.question === 'string') { pendingText.current = event.detail.question; if (input.current) input.current.value = pendingText.current; }
    };
    const syncCity = event => setCity(event.detail || initialCity());
    window.addEventListener('arogio:chat', handler); window.addEventListener('arogio:city', syncCity);
    // Compatibility bridge for existing Blade buttons and public-page scripts.
    window.toggleChatbot = () => window.dispatchEvent(new CustomEvent('arogio:chat'));
    window.selectChatbotCity = value => { const clean = String(value).trim(); if (!clean) return; try { localStorage.setItem('arogio_selected_city', clean); } catch {} setCity(clean); };
    window.submitChatbotMessage = message => window.dispatchEvent(new CustomEvent('arogio:chat', { detail: { open: true, question: message } }));
    return () => { window.removeEventListener('arogio:chat', handler); window.removeEventListener('arogio:city', syncCity); controller.current?.abort(); recognition.current?.stop(); };
  }, []);
  useEffect(() => { if (open && input.current) { input.current.value = pendingText.current; input.current.focus(); pendingText.current = ''; } if (!open) { recognition.current?.stop(); window.speechSynthesis?.cancel(); } }, [open]);
  useEffect(() => { scroll.current?.scrollTo({ top: scroll.current.scrollHeight }); }, [messages, busy, open]);
  function reset() { controller.current?.abort(); controller.current = null; token.current = null; setMessages([]); setBusy(false); setError(''); setLast(''); if (input.current) input.current.value = ''; window.speechSynthesis?.cancel(); }
  async function send(value, loadType = null, retry = false) {
    const text = String(value || '').trim(); if (busy || (!text && !loadType)) return;
    setBusy(true); setError(''); setLast(text);
    if (!retry && !loadType) setMessages(previous => [...previous, { sender: 'user', text }]);
    if (input.current) input.current.value = '';
    const request = new AbortController(); controller.current = request;
    const timer = setTimeout(() => request.abort(), 30000);
    try {
      const response = await fetch(href('api.chatbot'), { method: 'POST', signal: request.signal, headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': config.csrf }, body: JSON.stringify({ message: text, session_token: token.current, city, locale: config.locale, ...(loadType ? { load_type: loadType } : {}) }) });
      if (!response.ok) throw new Error('Request failed');
      const payload = await response.json();
      if (controller.current !== request) return;
      if (payload.session_token) token.current = payload.session_token;
      if (Array.isArray(payload.history) && payload.history.length) setMessages(payload.history.filter(m => ['user', 'bot'].includes(m.sender)));
      else if (payload.reply) setMessages(previous => [...previous, { ...payload, sender: 'bot', text: payload.reply }]);
      else throw new Error('Empty reply');
    } catch (e) { if (!request.signal.aborted || controller.current === request) setError(t('Jeeva could not respond. Please retry your message.', 'जीवा जवाब नहीं दे सका। कृपया संदेश दोबारा भेजें।')); }
    finally { clearTimeout(timer); if (controller.current === request) setBusy(false); }
  }
  function voice() {
    if (listening) { recognition.current?.stop(); return; }
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) { setError(t('Voice input is unavailable in this browser. Please type your question.', 'इस ब्राउज़र में आवाज़ से लिखना उपलब्ध नहीं है। प्रश्न टाइप करें।')); return; }
    const listener = new SpeechRecognition(); recognition.current = listener; listener.lang = config.locale === 'hi' ? 'hi-IN' : 'en-IN';
    listener.onresult = event => { if (input.current) input.current.value = event.results[0][0].transcript; };
    listener.onend = () => setListening(false);
    listener.onerror = () => { setListening(false); setError(t('Microphone access was unavailable. You can still type your question.', 'माइक्रोफ़ोन उपलब्ध नहीं है। प्रश्न टाइप कर सकते हैं।')); };
    try { listener.start(); setListening(true); } catch { setListening(false); }
  }
  function speak(text) { if (!window.speechSynthesis) return; window.speechSynthesis.cancel(); const speech = new SpeechSynthesisUtterance(String(text).replace(/[*_#]/g, '')); speech.lang = config.locale === 'hi' ? 'hi-IN' : 'en-IN'; window.speechSynthesis.speak(speech); }
  const suggestions = [t(`Pediatricians in ${city}`, `${city} में बाल रोग विशेषज्ञ`), t('Home remedies for dry cough', 'सूखी खाँसी के घरेलू नुस्खे'), t('Find a nearby blood bank', 'नज़दीकी ब्लड बैंक खोजें')];
  return <Box className="stitch-jeeva-root">
    <Button id="chatbot-toggle-btn" className="stitch-jeeva-launcher" variant="contained" startIcon={<Robot />} onClick={() => setOpen(!open)} aria-label={t('Open Jeeva assistant', 'जीवा सहायक खोलें')} aria-expanded={open} sx={{ position: 'fixed', right: { xs: 16, sm: 24 }, bottom: { xs: 78, md: 24 }, zIndex: 100, borderRadius: 10, px: 2.5, py: 1.5, display: open ? 'none' : 'inline-flex' }}>{t('Ask Jeeva', 'जीवा से पूछें')}</Button>
    <Paper id="chatbot-window" className={`stitch-jeeva-panel ${open ? '' : 'hidden'}`} role="dialog" aria-label={t('Jeeva health assistant', 'जीवा स्वास्थ्य सहायक')} aria-modal="false" sx={{ display: open ? 'flex' : 'none', flexDirection: 'column', position: 'fixed', right: { xs: 8, sm: 24 }, bottom: { xs: 72, md: 88 }, width: { xs: 'calc(100vw - 16px)', sm: 450 }, maxWidth: '100%', height: 'min(690px, calc(100dvh - 110px))', maxHeight: 'calc(100dvh - 110px)', zIndex: 120, overflow: 'hidden', borderRadius: 3, border: 1, borderColor: 'divider', boxShadow: '0 18px 55px rgba(23,43,58,.22)' }} onKeyDown={event => { if (event.key === 'Escape') setOpen(false); }}>
      <Stack direction="row" gap={1.5} alignItems="center" sx={{ px: 2, py: 1.5, bgcolor: 'action.hover', flexShrink: 0 }}><Box sx={{ bgcolor: 'primary.main', color: 'primary.contrastText', width: 36, height: 36, display: 'grid', placeItems: 'center', borderRadius: '50%' }}><Leaf fontSize="small" /></Box><Box sx={{ flex: 1 }}><Typography fontWeight={750}>Jeeva <Chip size="small" label={t('Healthcare AI', 'स्वास्थ्य AI')} sx={{ ml: .5, height: 21, fontSize: 10 }} /></Typography><Typography variant="caption" color="text.secondary">English / हिंदी</Typography></Box><Tooltip title={t('New conversation', 'नई बातचीत')}><IconButton aria-label={t('New conversation', 'नई बातचीत')} onClick={reset}><Refresh fontSize="small" /></IconButton></Tooltip><IconButton aria-label={t('Minimize Jeeva', 'जीवा छोटा करें')} onClick={() => setOpen(false)}><Minimize fontSize="small" /></IconButton><IconButton aria-label="Close AI assistant" onClick={() => setOpen(false)}><Close fontSize="small" /></IconButton></Stack>
      <Box sx={{ px: 2, py: 1, bgcolor: 'action.hover', borderBlock: 1, borderColor: 'divider', flexShrink: 0 }}><Typography variant="caption" color="text.secondary">{t('Informational guidance only • Not a medical diagnosis', 'केवल जानकारी • चिकित्सा निदान नहीं')}</Typography></Box>
      <Stack direction="row" alignItems="center" justifyContent="space-between" sx={{ px: 2, py: .5, borderBottom: 1, borderColor: 'divider', flexShrink: 0 }}><Button size="small" startIcon={<Place fontSize="small" />} onClick={() => { setCityInput(city); setCityDialog(true); }}>{city}</Button><Button size="small" href={href('emergency')} color="error">{t('Emergency help', 'आपात सहायता')}</Button></Stack>
      <Box id="chatbot-messages" ref={scroll} role="log" aria-live="polite" sx={{ p: 2, overflowY: 'auto', flex: 1, minHeight: 0, bgcolor: 'background.default' }}><Stack direction="row" gap={1}><Leaf fontSize="small" color="primary" /><Box><Paper variant="outlined" sx={{ p: 1.5, borderRadius: '0 12px 12px 12px' }}><Typography variant="body2">{t('Namaste! I am Jeeva, your health discovery assistant on Arogio. Describe your symptoms, find healthcare providers, or ask about health information.', 'नमस्ते! मैं जीवा, Arogio का स्वास्थ्य जानकारी सहायक हूँ। अपने लक्षण बताएँ, स्वास्थ्य सेवा खोजें या स्वास्थ्य जानकारी पूछें।')}</Typography></Paper>{!messages.length && <Stack gap={1} sx={{ mt: 2 }}>{suggestions.map(question => <Button key={question} disabled={busy} variant="outlined" size="small" sx={{ justifyContent: 'flex-start', textAlign: 'left', borderRadius: 5 }} onClick={() => send(question)}>{question}</Button>)}</Stack>}</Box></Stack>
        {messages.map((message, i) => <Box key={i} sx={{ mt: 2, ml: message.sender === 'user' ? 4 : 0, mr: message.sender === 'user' ? 0 : 2 }}><Paper variant={message.sender === 'user' ? undefined : 'outlined'} sx={{ p: 1.5, bgcolor: message.sender === 'user' ? 'primary.main' : 'background.paper', color: message.sender === 'user' ? 'primary.contrastText' : 'text.primary', borderRadius: message.sender === 'user' ? '12px 0 12px 12px' : '0 12px 12px 12px' }}><Typography variant="body2" sx={{ whiteSpace: 'pre-wrap', overflowWrap: 'anywhere' }}>{String(message.text || '').replace(/\*\*/g, '')}</Typography>{message.emergency && <Button href={href('emergency')} color="error">{t('Emergency resources', 'आपातकालीन संसाधन')}</Button>}{message.sender !== 'user' && <><ResourceCards message={message} />{message.qa_answer?.detailed_answer && message.qa_answer.detailed_answer !== message.text && <Box component="details" sx={{ mt: 1.5 }}><Typography component="summary" variant="body2" sx={{ cursor: 'pointer', color: 'primary.main' }}>{t('Read more', 'और पढ़ें')}</Typography><Typography variant="body2" sx={{ whiteSpace: 'pre-wrap', mt: 1 }}>{message.qa_answer.detailed_answer}</Typography></Box>}{message.medicine_info && <Button href={safeUrl(message.medicine_info.url) || href('medicines.index', { search: message.medicine_info.name })}>{t('Medicine information', 'दवा की जानकारी')}</Button>}</>}</Paper><Stack direction="row" gap={1} alignItems="center" justifyContent={message.sender === 'user' ? 'flex-end' : 'flex-start'}><Typography variant="caption" color="text.secondary">{message.sender === 'user' ? t('You', 'आप') : 'Jeeva'}</Typography>{message.sender !== 'user' && <IconButton size="small" aria-label={t('Read reply aloud', 'जवाब सुनें')} onClick={() => speak(message.text)} sx={{ minHeight: 30, minWidth: 30 }}><Volume sx={{ fontSize: 16 }} /></IconButton>}</Stack>{message.sender === 'bot' && message.show_options && <Stack direction="row" gap={.5} flexWrap="wrap">{[['doctors', t('Find doctors', 'डॉक्टर खोजें')], ['hospitals', t('Hospitals', 'अस्पताल')], ['articles', t('Articles', 'लेख')]].map(([type, label]) => <Button size="small" disabled={busy} key={type} onClick={() => send('', type)}>{label}</Button>)}</Stack>}</Box>)}
        {busy && <Stack direction="row" gap={1} alignItems="center" sx={{ mt: 2 }}><CircularProgress size={16} /><Typography variant="caption">{t('Jeeva is thinking…', 'जीवा विचार कर रहा है…')}</Typography></Stack>}{error && <Alert severity="error" sx={{ mt: 2 }} action={last ? <Button color="inherit" size="small" onClick={() => send(last, null, true)} disabled={busy}>{t('Retry', 'फिर कोशिश')}</Button> : undefined}>{error}</Alert>}
      </Box>
      <Box component="form" id="chatbot-form" onSubmit={event => { event.preventDefault(); send(input.current?.value); }} sx={{ p: 1.5, borderTop: 1, borderColor: 'divider', flexShrink: 0 }}><TextField fullWidth size="small" inputRef={input} id="chatbot-input" label={t('Ask a health question', 'स्वास्थ्य प्रश्न पूछें')} slotProps={{ htmlInput: { maxLength: 1000 }, input: { endAdornment: <InputAdornment position="end"><IconButton aria-label={t('Voice input', 'आवाज़ से लिखें')} onClick={voice} color={listening ? 'error' : 'default'}><Mic fontSize="small" /></IconButton><IconButton type="submit" disabled={busy} color="primary" aria-label="Send message"><Send fontSize="small" /></IconButton></InputAdornment> } }} /><Typography variant="caption" sx={{ display: 'block', color: 'error.main', mt: 1 }}>{t('Need urgent help? Use emergency resources.', 'तत्काल मदद चाहिए? आपातकालीन संसाधन देखें।')}</Typography></Box>
    </Paper>
    <Dialog open={cityDialog} onClose={() => setCityDialog(false)} fullWidth maxWidth="xs"><Box component="form" onSubmit={e => { e.preventDefault(); if (!cityInput.trim()) return; window.selectChatbotCity(cityInput); window.dispatchEvent(new CustomEvent('arogio:city', { detail: cityInput.trim() })); setCityDialog(false); }}><DialogTitle>{t('Choose your city', 'अपना शहर चुनें')}</DialogTitle><DialogContent><TextField fullWidth required autoFocus margin="dense" label={t('City', 'शहर')} value={cityInput} onChange={e => setCityInput(e.target.value)} /></DialogContent><DialogActions><Button onClick={() => setCityDialog(false)}>{t('Cancel', 'रद्द करें')}</Button><Button type="submit" variant="contained">{t('Update city', 'शहर अपडेट करें')}</Button></DialogActions></Box></Dialog>
  </Box>;
}
